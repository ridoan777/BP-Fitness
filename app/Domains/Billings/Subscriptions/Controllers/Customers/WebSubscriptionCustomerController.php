<?php

namespace App\Domains\Billings\Subscriptions\Controllers\Customers;

use App\Domains\Billings\Subscriptions\Models\SubscriptionAppUser;
use App\Helpers\Ui\{DatatableHelper, ConvertDynamicTimezone};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB, Log, Storage};
use Illuminate\{Http\Request, Validation\Rule};
use Carbon\Carbon;
use Exception;
use Throwable;

class WebSubscriptionCustomerController extends Controller
{
   public function index()
   {
      try {
         return view('Admin.sidebar.Billings.Customers.Subscribers.subscribers');
      } catch (Throwable $e) {
         return redirect()->back()->with('error', 'Action Error: ' . $e->getMessage());
      }
   }

   public function show(SubscriptionAppUser $subscription)
   {
      try {
         $subscription->load('subscribedAppUserRelatingBackTo_user', 'subscriberAppRelatingBackTo_Tier');

         return view('Admin.sidebar.Billings.Customers.Subscribers.show', compact('subscription'));
      } catch (Throwable $e) {
         return redirect()->back()->with('error', 'Action Error: ' . $e->getMessage());
      }

   }

   public function toggle(SubscriptionAppUser $subscriber)
   {
      try {
         $subscriber->update([
            'status' => !$subscriber->status,
         ]);

         return response()->json(['success' => true]);
      } catch (Exception $e) {
         return response()->json(['success' => false, 'error' => $e->getMessage()]);
      }
   }

   public function delete(SubscriptionAppUser $subscriber)
   {
      $user_name = null;
      $type = null;
      try {
         $user_name = $subscriber?->subscribedAppUserRelatingBackTo_user?->name ?? null;
         $type = "$" . ($subscriber?->amount ?? "N/A") . "/" . ($subscriber?->duration ?? "N/A") . " day(s)";
         $subscriber->delete();
         return redirect()->back()->with('success', "Subscription record of '{$user_name}' for '{$type}' is deleted successfully.");
      } catch (Exception $e) {
         return redirect()->back()->with('error', 'Action Error: ' . $e->getMessage())->withInput();
      }
   }

   public function bulkDelete(Request $request)
   {
      $request->validate([
         'ids' => 'required|array',
         'ids.*' => 'integer|exists:subscription_app_users,id',
      ]);

      $ids = $request->ids;

      $subscribers = SubscriptionAppUser::whereIn('id', $ids)->get();

      if ($subscribers->isEmpty()) {
         return back()->with('error', 'No valid subscribers selected.');
      }

      $MESSAGE = "Selected subscriptions are deleted.";
      DB::beginTransaction();
      // dd($ids, $subscribers);
      try {
         /** @var SubscriptionAppUser $subscriber */
         foreach ($subscribers as $subscriber) {
            $subscriber->delete();
         }

         DB::commit();

         return redirect()->back()->with('success', $MESSAGE);
      } catch (Throwable $e) {
         DB::rollBack();
         return back()->with('error', 'Bulk delete failed. No changes were made.');
      }
   }
}
