<?php

namespace App\Domains\Billings\Payments\Controllers\Customers;

use App\Domains\Billings\Shared\Enums\{PaymentStatusEnums};
use App\Domains\Billings\Subscriptions\Models\SubscriptionAppUser;
use App\Domains\E_Store\Shoppings\Models\ProductOrder;
use App\Helpers\Ui\{ConvertDynamicTimezone, DatatableHelper};
use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class WebOneTimeCustomerController extends Controller
{
   /*
      Due to proprietary secrecy, code that contained business rules were removed
   */
}
