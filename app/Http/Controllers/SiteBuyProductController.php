<?php

namespace App\Http\Controllers;

use App\Imports\MedmalRenewalsImport;
use App\Models\Cart;
use App\Models\Certificate;
use App\Models\ClientDetail;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Models\Signature;
use App\Notifications\BankTransferGatewayPayment;
use App\Notifications\PaypalGatewayPaymentCancel;
use App\Rules\ProjectDeclarationsRule;
use App\Services\DatasheetToPdfService;
use App\Services\LloydsDoprintService;
use App\Services\DeclarationsToPdfService;
use App\Services\PaymentCertificateToPdfService;
use App\Services\Yousign\YousignApiService;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;

class SiteBuyProductController extends Controller
{

    public function buy(Product $product, Cart $cart = null)
    {
        $user = auth()->user();

        if (!empty($cart)) {
            $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

            $next_step = $cart->product_step_id;

            switch ($next_step) {
                case 1:
                    return $this->step1($product, $cart);
                    break;
                case 2:
                    return $this->step2($product, $cart);
                    break;
                case 3:
                    return $this->step3($product, $cart);
                    break;
                case 4:
                    return $this->step4($product, $cart, request('payment'));
                    break;
                case 5:
                    return $this->step5($product, $cart, request('documentGeneration'));
                    break;
                case 6:
                    return $this->step6($product, $cart);
                    break;
                case 7:
                    return $this->step7($product, $cart);
                    break;
            }
        } else {
            // se cart assente o locked, genero un cart ref e creo il carrello
            $cart = Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_step_id' => 1,
                'expired_at' => now()->addMonths(1),
                'locked' => 'N',
                'cart_ref' => uniqid()
            ]);

            return redirect()->route('front.product.buy', ['product' => $product, 'cart' => $cart]);
        }
    }

    private function step1(Product $product, Cart $cart)
    {
        $user = auth()->user();
        // TODO verificare che i dati vengano estratti per specifico prodotto
        $clientDetails = ClientDetail::query()
            ->with('productClientDetails:field_name')
            ->where([
                'user_id' => $user->id
            ])
            ->latest('id')
            ->first();

        $product = $product->load([
            'productOffers'
        ]);

        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'detailed_description' => $product->detailed_description,
                'conditions' => $product->conditions,
                'conditional_fields' => $product->conditionalFields,
                'hex_color' => $product->hex_color,
                'offers' => $product->productOffers,
                'steps' => $product->productSteps->count()
            ],
            'cart' => $cart,
            'clientDetails' => $clientDetails
        ]);
    }

    public function store_step1(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $num_additional_fields = $product->conditionalFields->count();

        // TODO vedere come sfruttare UpdateClientDetailsRequest invece che dover ridefinire le regole uguali
        $validated = $request->validate([
            'user_id' => ['required'],
            'first_name' => ['required', 'max:25'],
            'last_name' => ['required', 'max:25'],
            'fiscal_code' => ['required', 'max:16'],
            'gender' => ['required', 'in:M,F', 'max:1'],
            'dob' => ['date'],
            'phone' => ['required', 'max:30', 'phone:IT,mobile'],
            'address' => ['required', 'max:150'],
            'city' => ['required', 'max:50'],
            'province' => ['required', 'max:2'],
            'country' => ['required', 'max:2'],
            'postal_code' => ['required', 'max:5'],
            'product_client_details' => ['required', 'array', 'min:' . $num_additional_fields, 'max:' . $num_additional_fields],
            'product_client_details.*.pivot.selected_field_value' => ['required'],
            'confirmation' => ['accepted']
        ]);

        DB::transaction(function () use ($user, $validated, $cart) {
            $clientDetails = ClientDetail::updateOrCreate(['user_id'   => $user->id], $validated);

            $rebuildProductClientDetails = [];
            foreach ($validated['product_client_details'] as $el) {
                $conditional_field_id = $el['pivot']['conditional_field_id'];
                $selected_field_value = $el['pivot']['selected_field_value'];
                $rebuildProductClientDetails[$conditional_field_id] = ['selected_field_value' => $selected_field_value];
            }

            $clientDetails->productClientDetails()->sync($rebuildProductClientDetails);

            $cart->updateCartStep(2);
        });

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function step2(Product $product, Cart $cart)
    {
        $user = auth()->user();
        $clientDetails = ClientDetail::query()
            ->where([
                'user_id' => $user->id
            ])->latest('id')->first();

        $flag_prev_2022 = $clientDetails->flag_prev_2022;

        $product = $product->load([
            'productOffers' => function ($query) use ($flag_prev_2022) {
                $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022);
            }
        ]);

        $conditionalFieldsIds = $product
            ->conditionalFields()
            ->select(['id'])
            ->where('only_client_details', 0)
            ->get()
            ->map(function ($v) {
                return $v['id'];
            });

        $productClientDetails = $clientDetails
            ->productClientDetails()
            ->select(['conditional_field_id', 'selected_field_value'])
            ->whereIn('conditional_field_id', $conditionalFieldsIds)
            ->get();

        $clean = []; // key conditional_field_id, value selected_field_value
        foreach ($productClientDetails as $productClientDetail) {
            $clean[$productClientDetail->conditional_field_id] = $productClientDetail->selected_field_value;
        }

        $offers = $product->productOffers->map(function ($rec) use ($clean) {
            $rec2 = $rec->conditionalFields;
            $flag = true;
            foreach ($rec2 as $bhgh) {
                $conditional_field_id = $bhgh->pivot->conditional_field_id;
                $selected_field_value = $bhgh->pivot->selected_field_value;
                if ($selected_field_value != $clean[$conditional_field_id]) {
                    $flag = false;
                }
            }
            if ($flag) {
                return $rec;
            }
        })->filter(function ($value) {
            return $value != null;
        });

        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'detailed_description' => $product->detailed_description,
                'conditions' => $product->conditions,
                'conditional_fields' => $product->conditionalFields,
                'hex_color' => $product->hex_color,
                'offers' => $offers,
                'steps' => $product->productSteps->count(),
            ],
            'cart' => $cart,
            'clientDetails' => $clientDetails
        ]);
    }

    public function store_step2(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $validated = $request->validate([
            'product_offer_id_selected' => ['required', 'integer'],
        ]);

        $productOffer = ProductOffer::findOrFail($request->product_offer_id_selected);
        $cart->total_payable = $productOffer->total_payable;
        $cart->admin_fee = $productOffer->admin_fee;
        $cart->gross_premium = $productOffer->gross_premium;
        $cart->tax = $productOffer->tax;
        $cart->net_premium = $productOffer->net_premium;
        $cart->accessory = $productOffer->accessory;
        $cart->net_plus_access_premium = $productOffer->net_plus_access_premium;
        $cart->indemnity_limit = $productOffer->indemnity_limit;
        $cart->indemnity_limit_patrimonial = $productOffer->indemnity_limit_patrimonial;

        $cart->product_offer_id = $request->product_offer_id_selected;
        $cart->save();

        $cart->updateCartStep(3);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function step3(Product $product, Cart $cart)
    {
        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'pre_declarations' => $product->pre_declarations,
                'post_declarations' => $product->post_declarations,
                'declarations' => $product->productDeclarations,
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count()
            ],
            'cart' => $cart
        ]);
    }

    public function store_step3(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $validated = $request->validate([
            'signature' => ['required', 'string', 'min:5'],
            'selected_declarations' => ['required', 'array', new ProjectDeclarationsRule($product->productDeclarations)],
            'selected_declarations.*' => ['integer', 'exists:product_declarations,id'],
        ]);

        // salvo nel db la firma delle dichiarazioni, da usare poi per la generazione del documento
        // nota: il salvataggio avviene poco sotto, nel metodo updateCartStep
        $cart->declarations_signature = $validated['signature'];

        // genero e salvo il pdf delle dichiarazioni
        $declarationToPdf = new DeclarationsToPdfService($cart);
        $declarationToPdf->generateAndPersist();

        $cart->updateCartStep(4);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function step4(Product $product, Cart $cart, $payment = null)
    {

        $productObject = [
            'id' => $product->id,
            'name' => $product->name,
            'type' => Str::slug($product->productType->name, '-'),
            'payments_description' => $product->payments_description,
            'hex_color' => $product->hex_color,
            'steps' => $product->productSteps->count(),
        ];

        if (empty($payment)) {
            $payment_gateways = PaymentGateway::where(['public' => 1])->get();
            $productObject['payment_gateways'] = $payment_gateways;

            return Inertia::render('Sites/BuyProduct', [
                'product' => $productObject,
                'cart' => $cart
            ]);
        } else {
            $payment_data = Payment::query()->where(['cart_id' => $cart->id])->findOrFail($payment);
            $productObject['payment'] = $payment_data;

            return Inertia::render('Sites/BuyProduct', [
                'product' => $productObject,
                'cart' => $cart
            ]);
        }
    }

    public function store_step4(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $validated = $request->validate([
            'payment_gateway_id_selected' => ['required', 'integer'],
        ]);

        // recupero o genero un record di payment
        $payment = Payment::firstOrCreate(
            [
                'cart_id' => $cart->id
            ],
            [
                'payment_gateway_id' => $validated['payment_gateway_id_selected'],
                'total_payable' => $cart->total_payable,
            ]
        );

        if ($validated['payment_gateway_id_selected'] == 1) {
            // paypal
            $url = $this->handle_step4paypal($cart, $product, $payment);
            return redirect()->route('front.paypal_redirect', ['url' => $url]);
        } else if ($validated['payment_gateway_id_selected'] == 2) {
            // bbm
            auth()->user()->notify(new BankTransferGatewayPayment($payment));
            return redirect()->route('front.product.buy', compact('product', 'cart', 'payment'));
        }
    }

    private function handle_step4paypal(Cart $cart, Product $product, Payment $payment)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        // Prepare Order
        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => 'transaction_id_' . $payment->id,
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => $cart->total_payable
                ]
            ]],
            'application_context' => [
                'cancel_url' => route('front.product.store_step4_paypal_cancel', [
                    'product' => $product,
                    'cart' => $cart,
                    'payment_id' => $payment->id
                ]),
                'return_url' => route('front.product.store_step4_paypal_return', [
                    'product' => $product,
                    'cart' => $cart,
                    'payment_id' => $payment->id
                ])
            ]
        ]);

        // Store Token so we can retrieve after PayPal sends them back to us
        $payment->transaction_ref = $order['id'];
        $payment->save();
        $paypal_redirect_url = $order['links'][1]['href'];

        return $paypal_redirect_url;
    }
    /**
     * Questo metodo mi serve per gestire la redirect esterna verso paypal per il pagamento
     */
    public function paypal_redirect(Request $request)
    {
        $url = $request->url;
        if (!empty($url)) {
            // funzionano entrambe
            //return redirect()->away($url);
            return Inertia::location($url);
        }
        abort(404);
    }

    public function store_step4_paypal_return(Product $product, Cart $cart)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder(request('token'));
        $payment_id = request('payment_id');

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $payment = Payment::query()->where(['cart_id' => $cart->id])->findOrFail($payment_id);
            $payment->updatePaymentStatus();
            $cart->updateCartStep(5);
        } else {
            return redirect()
                ->route('front.product.store_step4_paypal_cancel', [
                    'product' => $product,
                    'cart' => $cart,
                    'payment_id' => $payment_id
                ]);
        }

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    /**
     * Transazione PayPal non andata a buon fine
     * - Mostro al cliente l'errore interrompendo il flusso d'acquisto
     * - Invio una notifica via email al cliente e in copia a golinucci
     */
    public function store_step4_paypal_cancel(Product $product, Cart $cart)
    {
        // se l'utente cancella il pagamento con paypal lo riporto alla pagina di pagamento
        return redirect()->route('front.product.buy', compact('product', 'cart'));

        // $payment_id = request('payment_id');
        // $payment = Payment::query()->where(['cart_id' => $cart->id])->findOrFail($payment_id);
        // // invio email a cliente con notifica che il pagamento non è andato a buon fine
        // // bcc a MAIL_BCC_TO
        // $delay = now()->addMinutes(1);
        // auth()->user()->notify((new PaypalGatewayPaymentCancel($payment))->delay($delay));
        // return Inertia::render('Sites/PaymentPaypalCancel', compact('product', 'cart', 'payment'));
    }

    private function step5(Product $product, Cart $cart)
    {
        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count(),
                'documentGenerator' => [],
                'completedDocumentGenerator' => false
            ],
            'cart' => $cart,
        ]);
    }

    public function store_step5(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);
        $certificate = Certificate::firstOrCreate(
            ['cart_id' => $cart->id],
            [
                //'certificate_no' => $certificate_no, // set in certificate boot method
                'product_id' => $cart->product_id,
                'product_offer_id' => $cart->product_offer_id,
                'contract_id' => $cart->productOffer->contract->id,
                'start_validity_date' => $cart->renewal_date,
                'retroactivity' => $cart->retroactivity
            ]
        );
        if (isset($request->method)) {
            switch ($request->method) {
                case 'initialization':
                    $response = $this->store_step5_certificate_initialization($cart);
                    break;
                case 'handshake':
                    $response = $this->store_step5_certificate_handshake($cart);
                    break;
                case 'validation':
                    $response = $this->store_step5_certificate_validation($cart);
                    break;
                case 'upload':
                    $response = $this->store_step5_certificate_upload($cart);
                    break;
                case 'transaction':
                    $response = $this->store_step5_certificate_transaction($cart);
                    break;
                case 'download':
                    $response = $this->store_step5_certificate_download($cart);
                    break;
                case 'completed':
                    $response = $this->store_step5_certificate_download($cart);
                    break;
            }

            return response()->json($response);
        } else {
            $cart->updateCartStep(6);

            activity()
                ->causedBy($user)
                ->on($certificate)
                ->event('certificate_generation')
                ->log("Completed certificate $certificate->certificate_no generation;");

            return redirect()->route('front.product.buy', compact('product', 'cart'));
        }
    }

    private function store_step5_certificate_initialization(Cart $cart)
    {
        try {
            $certificate = $cart->certificate;
            // genero e salvo il pdf con proforma fattura dell'acquisto
            $paymentToPdf = new PaymentCertificateToPdfService($cart);
            $paymentToPdf->generateAndPersist();
            $user = auth()->user();
            if (empty($certificate->doprint_token)) {
                activity()
                    ->causedBy($user)
                    ->on($certificate)
                    ->event('certificate_generation')
                    ->log("Initiate certificate $certificate->certificate_no generation;");
            } else {
                activity()
                    ->causedBy($user)
                    ->on($certificate)
                    ->event('certificate_generation')
                    ->log("Re-Initiate certificate $certificate->certificate_no generation due to previous error;");
            }

            return [
                'error' => false,
                'message' => 'Inizializzazione certificato completata...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on initialization;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Inizializzazione certificato fallita. Si prega di contattarci via telefono o email'
            ];
        }
    }

    private function store_step5_certificate_handshake(Cart $cart)
    {
        try {
            $certificate = $cart->certificate;
            // se validation allora rieseguo anche handshake
            if ($certificate->doprint_current_step == 'handshake' && empty($certificate->doprint_token) || $certificate->doprint_current_step == 'validation' && !empty($certificate->doprint_token)) {
                $certificate->doprint_token = LloydsDoprintService::handShake();
                $certificate->doprint_current_step = 'validation';
                $certificate->save();
            }

            return [
                'error' => false,
                'message' => 'Connessione avvenuta correttamente...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on handshake;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Connessione fallita. Si prega di contattarci via telefono o email'
            ];
        }
    }

    private function store_step5_certificate_validation(Cart $cart)
    {
        try {
            $user = auth()->user();
            $certificate = $cart->certificate;
            if ($certificate->doprint_current_step == 'validation') {
                $clientDetails = ClientDetail::where('user_id', $cart->user_id)->first();
                $profession = $clientDetails->getConditionalFieldMatchById(1);
                $authority_type = $clientDetails->getConditionalFieldMatchById(2);

                $customer = [
                    'profession' => $profession,
                    'authority_type' => $authority_type,
                    'first_name' => $clientDetails->first_name,
                    'last_name' => $clientDetails->last_name,
                    'address' => $clientDetails->address,
                    'city' => $clientDetails->city,
                    'postal_code' => $clientDetails->postal_code,
                    'province' => $clientDetails->province, //'MI',
                    'fiscal_code' => $clientDetails->fiscal_code,
                    'gender' => $clientDetails->gender,
                    'country' => ($clientDetails->country == 'IT') ? 'Italia' : $clientDetails->country
                ];

                $cart_details = [
                    'cart_id' => $cart->id,
                    'price' => $cart->net_premium,
                    'accessori' => $cart->accessory, // 10% di sovrapprezzo sul netto; 44/10
                    'tax' => $cart->tax, // tasse calcolate su premio + accessori
                    'price_with_tax' => $cart->gross_premium, // premio + accessori + tax (no fee)
                ];

                $datasheetToPdf = new DatasheetToPdfService($cart);
                $scheda = $datasheetToPdf->generateAndPersist($profession); // passo profession perché già generato poco sopra

                $isValid = LloydsDoprintService::validationRequest(
                    $cart,
                    $customer,
                    $cart_details,
                    $scheda
                );
                if ($isValid) {
                    $certificate->doprint_current_step = 'upload';
                    $certificate->save();
                }
            }
            return [
                'error' => false,
                'message' => 'Validazione certificato completata...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on validation;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Validazione certificato fallita. Si prega di contattarci via telefono o email'
            ];
        }
    }

    private function store_step5_certificate_upload(Cart $cart)
    {

        try {
            $user = auth()->user();
            $certificate = $cart->certificate;
            if ($certificate->doprint_current_step == 'upload') {
                LloydsDoprintService::uploadAttachments($cart);
                $certificate->doprint_current_step = 'transaction';
                $certificate->save();
            }

            return [
                'error' => false,
                'message' => 'Upload file completato...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on upload;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Upload file fallito. Si prega di contattarci via telefono o email'
            ];
        }
    }

    private function store_step5_certificate_transaction(Cart $cart)
    {
        try {
            $user = auth()->user();
            $certificate = $cart->certificate;
            if ($certificate->doprint_current_step == 'transaction') {
                $isValid = LloydsDoprintService::transactionRequest($cart);
                if ($isValid) {
                    $certificate->doprint_current_step = 'download';
                    $certificate->save();
                }
            }

            return [
                'error' => false,
                'message' => 'Transazione completata...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on transaction;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Transazione fallita. Si prega di contattarci via telefono o email'
            ];
        }
    }

    private function store_step5_certificate_download(Cart $cart)
    {
        try {
            $user = auth()->user();
            $certificate = $cart->certificate;
            if ($certificate->doprint_current_step == 'download') {
                // // Download the certificate
                $filename = 'certificate.pdf';
                $isValid = LloydsDoprintService::downloadRequest($cart, $filename);
                if ($isValid) {
                    $certificate->doprint_current_step = 'completed';
                    $certificate->save();
                }
            }
            return [
                'error' => false,
                'message' => 'Preparazione certificato completata...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on Download;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Preparazione certificato fallita. Si prega di contattarci via telefono o email'
            ];
        }
    }

    private function step6(Product $product, Cart $cart)
    {
        try {
            $yousignClient = new YousignApiService($cart);
            $signature = Signature::where('certificate_id', $cart->certificate->id)->first();

            if (empty($signature)) {
                $yousignMemberSignerId = $yousignClient->startYousignClientAndLaunchProcedure();
            } else {
                $yousignMemberSignerId = $signature->member_signer_id;
            }
        } catch (\Exception $e) {
            $product_signature_error = 'Si è verificato un errore con la procedura di firma.';
        }

        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count(),
                'yousign_url' => config('yousign.app_url'),
                'yousign_member_signer_id' => $yousignMemberSignerId ?? null,
                'signature_error' => $product_signature_error ?? null
            ],
            'cart' => $cart
        ]);
    }

    private function step7(Product $product, Cart $cart)
    {
        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count()
            ],
            'cart' => $cart
        ]);
    }

    private function verifyAndGetCartOrRedirect(User $user, Product $product, Cart $cart)
    {
        if (!empty($cart)) {
            // recupero il carrello verificando se è ancora attivo
            if (empty($cart->verifyAndGrabCart($user))) {
                abort(401);
            }
        }

        return $cart;
    }

    // public function testImport()
    // {
    //     Excel::import(new MedmalRenewalsImport, storage_path('app/public/' . 'database-giugno-settembre.csv'));
    // }
}
