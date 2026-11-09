<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Certificate;
use App\Models\ClientDetail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Runs the Lloyd's DoPrint chain after payment so the buyer does not click Start.
 */
class LloydsDoprintPipelineService
{
    public function run(Cart $cart): void
    {
        $lockName = 'doprint-cart-' . $cart->id;
        $locked = DB::selectOne('SELECT GET_LOCK(?, 120) as l', [$lockName]);
        if (!$locked || (int) $locked->l !== 1) {
            return;
        }

        try {
            $cart->loadMissing(['user', 'product', 'productOffer.contract', 'certificate']);
            $certificate = $this->ensureCertificate($cart);
            $cart->setRelation('certificate', $certificate);

            if ($this->isFinished($cart, $certificate)) {
                return;
            }

            $this->initialize($cart, $certificate);
            $this->handshake($cart, $certificate);
            $this->validation($cart, $certificate);
            $this->upload($cart, $certificate);
            $this->transaction($cart, $certificate);
            $this->download($cart, $certificate);

            $certificate->refresh();
            if ($certificate->doprint_current_step === 'completed' && (int) $cart->product_step_id < 6) {
                $cart->updateCartStep(6);
                Cache::forget($this->errorKey($cart));
                $this->logCertificate($cart, $certificate, "Completed certificate {$certificate->id} generation;");
            }
        } finally {
            DB::select('SELECT RELEASE_LOCK(?)', [$lockName]);
        }
    }

    public function status(Cart $cart): array
    {
        $cart->loadMissing('certificate');
        $certificate = $cart->certificate;
        $step = $certificate->doprint_current_step ?? null;
        $finished = $this->isFinished($cart, $certificate);
        $lastError = Cache::get($this->errorKey($cart));

        if ($lastError && !$finished) {
            return [
                'error' => true,
                'doprint_step' => $step,
                'cart_step' => (int) $cart->product_step_id,
                'completed' => false,
                'message' => $lastError,
            ];
        }

        return [
            'error' => false,
            'doprint_step' => $step,
            'cart_step' => (int) $cart->product_step_id,
            'completed' => $finished,
            'message' => $this->messageFor($step, (int) $cart->product_step_id, $finished),
        ];
    }

    private function isFinished(Cart $cart, ?Certificate $certificate): bool
    {
        if ((int) $cart->product_step_id >= 6) {
            return true;
        }

        return $certificate && $certificate->doprint_current_step === 'completed';
    }

    private function ensureCertificate(Cart $cart): Certificate
    {
        return Certificate::firstOrCreate(
            ['cart_id' => $cart->id],
            [
                'product_id' => $cart->product_id,
                'product_offer_id' => $cart->product_offer_id,
                'contract_id' => $cart->productOffer->contract->id,
                'start_validity_date' => $cart->renewal_date,
                'retroactivity' => $cart->retroactivity,
            ]
        );
    }

    private function initialize(Cart $cart, Certificate $certificate): void
    {
        if ($certificate->doprint_current_step == null) {
            $certificate->doprint_current_step = 'handshake';
            $certificate->save();
        }

        $this->logCertificate(
            $cart,
            $certificate,
            empty($certificate->doprint_token)
                ? "Initiate certificate {$certificate->id} generation;"
                : "Re-Initiate certificate {$certificate->id} generation due to previous error;"
        );
    }

    private function handshake(Cart $cart, Certificate $certificate): void
    {
        $certificate->refresh();
        $needsHandshake = ($certificate->doprint_current_step == 'handshake' && empty($certificate->doprint_token))
            || ($certificate->doprint_current_step == 'validation' && !empty($certificate->doprint_token));

        if (!$needsHandshake) {
            return;
        }

        try {
            $certificate->doprint_token = LloydsDoprintService::handShake();
            $certificate->doprint_current_step = 'validation';
            $certificate->save();
        } catch (Throwable $e) {
            $this->fail($cart, $certificate, 'handshake', $e);
        }
    }

    private function validation(Cart $cart, Certificate $certificate): void
    {
        $certificate->refresh();
        if ($certificate->doprint_current_step != 'validation') {
            return;
        }

        try {
            $clientDetails = ClientDetail::where('user_id', $cart->user_id)->first();
            $profession = null;
            $authority_type = null;

            if ($cart->product->id == 1) {
                $profession = $clientDetails->getConditionalFieldMatchById(1);
                $authority_type = $clientDetails->getConditionalFieldMatchById(2);
            }
            if ($cart->product->id == 5) {
                $profession = 'GIOVANE MEDICO';
                $authority_type = 'nulla';
            }

            $customer = [
                'profession' => $profession,
                'authority_type' => $authority_type,
                'first_name' => $clientDetails->first_name,
                'last_name' => $clientDetails->last_name,
                'address' => $clientDetails->address,
                'city' => $clientDetails->city,
                'postal_code' => $clientDetails->postal_code,
                'province' => $clientDetails->province,
                'fiscal_code' => $clientDetails->fiscal_code,
                'gender' => $clientDetails->gender,
                'country' => ($clientDetails->country == 'IT') ? 'Italia' : $clientDetails->country,
            ];

            $cart_details = [
                'cart_id' => $cart->id,
                'price' => $cart->net_premium,
                'accessori' => $cart->accessory,
                'tax' => $cart->tax,
                'price_with_tax' => $cart->gross_premium,
                'indemnity_limit' => $cart->indemnity_limit,
            ];

            $scheda = (new DatasheetToPdfService($cart))->generateAndPersist($profession);
            $appFolder = 'certificates/user_' . $cart->user_id . '/cart_' . $cart->id;
            if (!Storage::disk('private')->exists($appFolder . '/new-application.pdf')) {
                (new NewApplicationToPdfService($cart))->generateAndPersist();
            }
            $isValid = LloydsDoprintService::validationRequest($cart, $customer, $cart_details, $scheda);
            if ($isValid) {
                $certificate->doprint_current_step = 'upload';
                $certificate->save();
            }
        } catch (Throwable $e) {
            $this->fail($cart, $certificate, 'validation', $e);
        }
    }

    private function upload(Cart $cart, Certificate $certificate): void
    {
        $certificate->refresh();
        if ($certificate->doprint_current_step != 'upload') {
            return;
        }

        try {
            LloydsDoprintService::uploadAttachments($cart);
            $certificate->doprint_current_step = 'transaction';
            $certificate->save();
        } catch (Throwable $e) {
            $this->fail($cart, $certificate, 'upload', $e);
        }
    }

    private function transaction(Cart $cart, Certificate $certificate): void
    {
        $certificate->refresh();
        if ($certificate->doprint_current_step != 'transaction') {
            return;
        }

        try {
            if (LloydsDoprintService::transactionRequest($cart)) {
                $certificate->doprint_current_step = 'download';
                $certificate->save();
            }
        } catch (Throwable $e) {
            $this->fail($cart, $certificate, 'transaction', $e);
        }
    }

    private function download(Cart $cart, Certificate $certificate): void
    {
        $certificate->refresh();
        if ($certificate->doprint_current_step != 'download') {
            return;
        }

        try {
            if (LloydsDoprintService::downloadRequest($cart, 'certificate.pdf')) {
                $certificate->doprint_current_step = 'completed';
                $certificate->save();
            }
        } catch (Throwable $e) {
            $this->fail($cart, $certificate, 'download', $e);
        }
    }

    private function fail(Cart $cart, Certificate $certificate, string $step, Throwable $e): void
    {
        $this->logCertificate($cart, $certificate, "An error occurred on generating a certificate on {$step};" . $e->getMessage());
        Log::error('doprint.pipeline_failed', [
            'cart_ref' => $cart->cart_ref,
            'step' => $step,
            'message' => $e->getMessage(),
        ]);
        Cache::put(
            $this->errorKey($cart),
            'Lloyd\'s non ha accettato la connessione. Il pagamento e a posto, i documenti no. Riprova tra poco o contattaci al 0547 22351.',
            3600
        );

        throw $e;
    }

    private function logCertificate(Cart $cart, Certificate $certificate, string $message): void
    {
        $activity = activity()
            ->on($certificate)
            ->event('certificate_generation');

        if ($cart->user) {
            $activity->causedBy($cart->user);
        }

        $activity->log($message);
    }

    private function messageFor(?string $step, int $cartStep, bool $finished): string
    {
        if ($finished || $cartStep >= 6) {
            return 'Documenti pronti. Stiamo aprendo il passo successivo...';
        }

        $map = [
            null => 'Preparazione documenti in corso...',
            'handshake' => 'Connessione ai documenti in corso...',
            'validation' => 'Validazione certificato in corso...',
            'upload' => 'Invio file in corso...',
            'transaction' => 'Transazione in corso...',
            'download' => 'Preparazione certificato in corso...',
            'completed' => 'Documenti pronti. Stiamo aprendo il passo successivo...',
        ];

        return $map[$step] ?? 'Generazione documenti in corso...';
    }

    private function errorKey(Cart $cart): string
    {
        return 'doprint-last-error-' . $cart->id;
    }
}
