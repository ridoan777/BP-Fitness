<?php

namespace App\Domains\Billings\Shared\Controllers\Webhooks;

use App\Domains\Achievements\Services\EStoreAchievement;
use App\Domains\Billings\Payments\Models\PaymentTrx;
use App\Domains\Billings\Shared\Enums\{PaymentStatusEnums, TrxPhaseEnums};
use App\Domains\E_Store\Shoppings\Enums\{DeliveryStatusEnums, OrderStatusEnums};
use App\Domains\E_Store\Shoppings\Events\OneTimeCheckoutNotifyEvent;
use App\Domains\E_Store\Shoppings\Models\{ProductOrder, ProductOrderItem};
use App\Domains\E_Store\Products\Models\ProductSkuSellable;
use App\Enums\CRUDActionEnums;
use App\Enums\NotifyChannelEnums;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log};
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Stripe\Exception\SignatureVerificationException;
use Throwable;

class StripePaymentWebhookController extends CashierWebhookController
{
	public function handleWebhook(Request $request)
	{
		# Due to proprietary secrecy, code that contained business rules were removed
	}

	// -------------- 1| CHECKOUT SESSION COMPLETED --------------

	protected function handleCheckoutSessionCompleted(array $payload)	// only updates paymentTrx
	{
	}
	// -------------- 2| handleCheckoutSessionCompleted --------------

	protected function handlePaymentIntentSucceeded(array $payload)
	{
	}
	// -------------- 3| CHARGE UPDATED (PAYMENT SUCCESS) --------------

	protected function handleChargeUpdated(array $payload)
	{
	}
	// -------------- 4| SESSION EXPIRED --------------

	protected function handleCheckoutSessionExpired(array $payload)
	{
	}
	// -------------- 5| INTENT FAILED --------------

	protected function handlePaymentIntentPaymentFailed(array $payload)
	{
	}

	// -------------- HELPER METHODS --------------

	protected function showLog($env, $methodName, $logBody = [])
	{
	}

	private function paymentSourceOrder(PaymentTrx $paymentTrx, bool $lock = false): ?ProductOrder
	{
	}

	private function resolvePaymentTrxFromIntent(\Stripe\StripeClient $stripe, string $paymentIntent): ?PaymentTrx
	{
	}

	private function appendEventTrace(array $savedEvents, array $payload): array
	{
	}

	private function appendSystemNote(?string $currentNote, string $newNote): string
	{
	}

	private function isPaidOrCompleted(PaymentTrx $paymentTrx): bool
	{
	}
}
