<?php

namespace App\Domains\Billings\Payments\Controllers\Transactions;

use App\Domains\Billings\Payments\Models\PaymentTrx;
use App\Domains\Billings\Shared\Enums\TrxPhaseEnums;
use App\Domains\E_Store\Shoppings\Models\ProductOrder;
use App\Domains\E_Store\Shoppings\Models\ProductOrderItem;
use App\Helpers\Ui\{DatatableHelper, ConvertDynamicTimezone};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Illuminate\{Http\Request, Support\Str, Validation\Rule};
use Throwable;

class WebPaymentTransactionController extends Controller
{
   # Due to proprietary secrecy, code that contained business rules were removed
}
