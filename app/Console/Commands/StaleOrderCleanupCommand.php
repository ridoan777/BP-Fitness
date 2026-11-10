<?php

namespace App\Console\Commands;

use App\Domains\Billings\Payments\Models\PaymentTrx;
use App\Domains\Billings\Shared\Enums\{TrxPhaseEnums,PaymentStatusEnums};
use App\Domains\E_Store\Shoppings\Enums\OrderStatusEnums;
use App\Domains\E_Store\Shoppings\Models\ProductOrder;
use App\Helpers\Errors\LoggerAccess;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StaleOrderCleanupCommand extends Command
{
   protected $signature = 'orders:mark-failed';
   protected $description = 'Mark stale product orders as failed when no successful payment transaction exists.';

   public function handle(): int
   {
      $cutoff = now()->subHours(120);  // 24 hours * 5 days = 120 hours

      $staleOrdersQuery = ProductOrder::query()
         ->where('updated_at', '<', $cutoff)
         ->where(function ($query) {
            $query->whereNull('order_status')
               ->orWhereNotIn('order_status', OrderStatusEnums::accessGrantedValues());
         })
         ->orderBy('id');

      $totalUpdated = 0;

      $staleOrdersQuery->chunkById(200, function ($orders) use (&$totalUpdated) {
         $failOrderIds = [];

         foreach ($orders as $order) {
            $successfulTrxExists = PaymentTrx::query()
               ->paymentSourceType(ProductOrder::class)
               ->paymentSourceId((int)$order->id)
               ->whereIn('trx_phase', TrxPhaseEnums::accessGrantedValues())
               ->exists();

            if ($successfulTrxExists) {
               LoggerAccess::showLog(['local', 'staging'], 'info', "stale order cleanup from StaleOrderCleanupCommand skipped order because successful trx exists", [
                  'order_id' => $order->id,
                  'order_uid' => $order->order_uid,
               ]);
               continue;
            }

            $failOrderIds[] = $order->id;
         }

         if (empty($failOrderIds)) {
            return;
         }

         DB::transaction(function () use ($failOrderIds, &$totalUpdated) {
            $orderIds = $failOrderIds;

            $updated = ProductOrder::query()
               ->whereIn('id', $orderIds)
               ->update([
                  'order_status' => OrderStatusEnums::FAILED->value,
                  'payment_status' => PaymentStatusEnums::FAILED->value,
               ]);

            $totalUpdated += $updated;

            LoggerAccess::showLog(['local', 'staging'], 'info', "stale order cleanup from StaleOrderCleanupCommand marked these orders as failed", [
               'updated_count' => $updated,
               'order_ids' => $orderIds,
            ]);
         });
      });

      $this->info("Marked {$totalUpdated} stale order(s) as failed.");

      return self::SUCCESS;
   }
}
