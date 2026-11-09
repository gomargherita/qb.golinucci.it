<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Services\LloydsDoprintPipelineService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Recovery for Medici carts paid but stuck before DoPrint completed (KAN-6 / KAN-38).
 *
 * Dry-run by default; pass --apply to re-run the pipeline from the saved step.
 */
class ReconcileDoprintStuck extends Command
{
    protected $signature = 'doprint:reconcile-stuck
                            {--apply : Re-run the DoPrint pipeline}
                            {--limit=20 : Max carts to inspect}
                            {--days=30 : Only payments paid in the last N days}';

    protected $description = 'Retry Lloyd\'s DoPrint for paid Medici carts stuck before certificate download';

    public function handle(LloydsDoprintPipelineService $pipeline): int
    {
        $apply = (bool) $this->option('apply');
        $limit = max(1, (int) $this->option('limit'));
        $days = max(1, (int) $this->option('days'));

        $carts = Cart::query()
            ->with(['certificate', 'payment', 'product'])
            ->where('product_step_id', 5)
            ->whereHas('product', function ($q) {
                $q->where('product_type_id', 1);
            })
            ->whereHas('payment', function ($q) use ($days) {
                $q->where('paid', 1)
                    ->where('paid_at', '>=', now()->subDays($days));
            })
            ->where(function ($q) {
                $q->whereDoesntHave('certificate')
                    ->orWhereHas('certificate', function ($c) {
                        $c->whereNull('doprint_current_step')
                            ->orWhere('doprint_current_step', '!=', 'completed');
                    });
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();

        $this->info(($apply ? 'APPLY' : 'DRY-RUN') . " — inspecting {$carts->count()} cart(s)");

        $ok = 0;
        $skip = 0;
        $fail = 0;

        foreach ($carts as $cart) {
            $step = optional($cart->certificate)->doprint_current_step ?: 'null';
            $label = "cart={$cart->cart_ref} id={$cart->id} doprint={$step}";

            if (!$apply) {
                $this->info("WOULD RETRY {$label}");
                $ok++;
                continue;
            }

            try {
                $pipeline->run($cart);
                $cart->refresh();
                $cart->load('certificate');
                $newStep = optional($cart->certificate)->doprint_current_step ?: 'null';
                $finished = $newStep === 'completed' || (int) $cart->product_step_id >= 6;

                Log::info('doprint.reconcile', [
                    'cart_ref' => $cart->cart_ref,
                    'step' => $newStep,
                    'finished' => $finished,
                ]);

                if ($finished) {
                    $this->info("COMPLETED {$label} → {$newStep}");
                    $ok++;
                } else {
                    $this->warn("STILL PENDING {$label} → {$newStep}");
                    $skip++;
                }
            } catch (Throwable $e) {
                Log::warning('doprint.reconcile_failed', [
                    'cart_ref' => $cart->cart_ref,
                    'message' => $e->getMessage(),
                ]);
                $this->error("FAILED {$label} — " . $e->getMessage());
                $fail++;
            }
        }

        $this->info("Summary: ok={$ok} pending={$skip} failed={$fail}");

        return $fail > 0 ? 1 : 0;
    }
}
