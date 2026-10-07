<?php

namespace App\Console\Commands;

use App\Models\Signature;
use App\Services\Yousign\YousignApiLaravel;
use App\Services\Yousign\YousignProcedureCompletionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Recovery path for signatures stuck before procedure_finished (KAN-5).
 *
 * Dry-run by default; pass --apply to complete practices whose Yousign
 * signature request is already done/completed.
 */
class ReconcileYousignStuck extends Command
{
    protected $signature = 'yousign:reconcile-stuck
                            {--apply : Persist completion (download PDF, step 7, final email)}
                            {--limit=50 : Max signatures to inspect}';

    protected $description = 'Reconcile Yousign signatures stuck before procedure_finished';

    public function handle(YousignProcedureCompletionService $completion): int
    {
        $apply = (bool) $this->option('apply');
        $limit = max(1, (int) $this->option('limit'));

        $stuckStatuses = ['init', 'server_stamp_ok', 'member_ok', 'member_finished'];

        $signatures = Signature::query()
            ->whereIn('status', $stuckStatuses)
            ->whereNotNull('procedure_id')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        $this->info(($apply ? 'APPLY' : 'DRY-RUN') . " — inspecting {$signatures->count()} signature(s)");

        $client = new YousignApiLaravel();
        $done = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($signatures as $signature) {
            $remote = $client->getSignatureRequest($signature->procedure_id);
            $remoteStatus = is_array($remote) ? ($remote['status'] ?? null) : null;

            $cart = $completion->findCart(null, $signature->procedure_id);
            $cartRef = $cart ? $cart->cart_ref : 'n/a';

            if (!$remoteStatus) {
                $this->warn("id={$signature->id} cart={$cartRef} procedure={$signature->procedure_id} — remote status unknown");
                $skipped++;
                continue;
            }

            $isDone = in_array(strtolower((string) $remoteStatus), ['done', 'completed', 'finished'], true);
            if (!$isDone) {
                $this->line("id={$signature->id} cart={$cartRef} local={$signature->status} remote={$remoteStatus} — skip");
                $skipped++;
                continue;
            }

            if (!$cart) {
                $this->error("id={$signature->id} procedure={$signature->procedure_id} — cart missing");
                $failed++;
                continue;
            }

            if (!$apply) {
                $this->info("WOULD COMPLETE id={$signature->id} cart={$cartRef} remote={$remoteStatus}");
                $done++;
                continue;
            }

            $result = $completion->complete($cart, $signature->procedure_id);
            Log::info('yousign.reconcile', $result);

            if ($result['ok']) {
                $this->info("COMPLETED cart={$cartRef} reason={$result['reason']}");
                $done++;
            } else {
                $this->error("FAILED cart={$cartRef} reason={$result['reason']}");
                $failed++;
            }
        }

        $this->info("Summary: actionable/completed={$done} skipped={$skipped} failed={$failed}");

        return $failed > 0 ? 1 : 0;
    }
}
