<?php

namespace App\Domains\Billings\Payments\Services;

use App\Domains\Billings\Payments\Models\PaymentTrx;
use App\Domains\Billings\Shared\Enums\{PaymentStatusEnums,TrxPhaseEnums};
use App\Helpers\UidGenerator;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class PaymentTrxService
{
   public function initialize(User $payer, string $appCurrency, string $BASE_CLASS_NAME, ?Model $BASE_CLASS_OBJECT): PaymentTrx
   {
      return PaymentTrx::create([
         'trx_uid' => UidGenerator::uniqueULID("TRX", 5, 15, 4),
         'user_id' => $payer->id,

			'payment_source_type' => $BASE_CLASS_NAME,
         'payment_source_id' => $BASE_CLASS_OBJECT->id,
         
         'ss_user_id' => $payer->id,
         'ss_user_email' => $payer->email ?? null,

         'payment_status' => PaymentStatusEnums::default(),
         'trx_phase' => TrxPhaseEnums::default(),

         'currency' => $appCurrency,

         'trx_amount' => 0,
         'stripe_fee' => 0,
         'exchange_rate' => 0,
         'takeaway_amount' => 0,

         'card_details' => null,
         'customer_details' => null,
         'payment_intent' => null,
         'event_ids' => null,
         'stripe_checkout_session_id' => null,
         
         'system_note' => null,

         'status' => 1,
      ]);
   }
}
