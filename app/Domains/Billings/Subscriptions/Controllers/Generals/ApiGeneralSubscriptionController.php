<?php

namespace App\Domains\Billings\Subscriptions\Controllers\Generals;

use App\Domains\Billings\Subscriptions\Models\SubscriptionAppUser;
use App\Http\Controllers\Controller;
use App\Helpers\{ApiJsonReturnHelper, Errors\ExceptionHandling};
use Illuminate\{Http\Request, Validation\Rule};
use Throwable;

class ApiGeneralSubscriptionController extends Controller
{
   public function status(Request $request)
   {
      try {
         $USER = $request->user();

         $subscription = SubscriptionAppUser::userId($USER->id)->latest('id')->first();
         $activeSubscription = SubscriptionAppUser::userId($USER->id)->active()->latest('expire_at')->first();

         $data = [
            'has_active_subscription' => (bool)$activeSubscription,
            'active_subscription' => $activeSubscription,
         ];

         return ApiJsonReturnHelper::handle(true, 200, "Subscription status has been fetched successfully!", $data);
      } catch (Throwable $e) {
         return ExceptionHandling::handle('fetching subscription status', $e);
      }
   }
}
