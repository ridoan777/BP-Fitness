<?php

namespace App\Domains\Billings\Subscriptions\Controllers\Transactions;

use App\Domains\Billings\Shared\Enums\TrxPhaseEnums;
use App\Domains\Billings\Subscriptions\Models\SubscriptionTrx;
use App\Helpers\Ui\{DatatableHelper, ConvertDynamicTimezone};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Illuminate\{Http\Request, Support\Str, Validation\Rule};
use Throwable;

class WebSubscriptionTransactionController extends Controller
{
   # Due to proprietary secrecy, code that contained business rules were removed
   public function index()
   {
   }

   public function show(SubscriptionTrx $transaction)
   {
   }


   public function toggle(SubscriptionTrx $transaction)
   {
   }

   public function delete(SubscriptionTrx $transaction)
   {
   }

   public function bulkDelete(Request $request)
   {
   }

   public function datatable(Request $request)
   {
   }
}
