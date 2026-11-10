<?php

namespace App\Domains\Billings\Shared\Controllers\Webhooks;

use App\Domains\Billings\Shared\Enums\{GatewayProviderEnums, PaymentStatusEnums, RevenueCatProductEnums, SubscriptionStatusEnums, TrxPhaseEnums};
use App\Domains\Billings\Subscriptions\Models\{SubscriptionTier, SubscriptionAppUser, SubscriptionTrx};
use App\Helpers\ApiJsonReturnHelper;
use App\Helpers\Errors\{ExceptionHandling, LoggerAccess};
use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log};
use Throwable;

class RevenueCatWebhookController extends Controller
{
	public function handleWebhook(Request $request)
	{
		# Due to proprietary secrecy, code that contained business rules were removed
	}

	// -------------------- EVENT HANDLERS --------------------

	protected function handleInitialPurchase(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleRenewal(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleUncancellation(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleNonRenewingPurchase(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleProductChange(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleSubscriptionExtended(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleTransfer(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleRefund(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	// ----------- Issue Events -----------

	protected function handlePaused(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleCancellation(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleExpiration(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleBillingIssue(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	protected function handleGracePeriod(array $event, User $user, ?SubscriptionTrx $TrxEntry = null)
	{
	}

	// ----------- Issue Events -----------

	public function handleTestEvent(Request $request)
	{
	}
	// -------------------- EVENT HANDLERS --------------------


	// -------------------- HELPERS --------------------
	protected function successResponse(string $message, $transactionEntry = null)
	{
	}

	protected function resolveUserId(string $appUserId): ?int
	{
	}

	protected function findRecord(array $event, User $user): ?SubscriptionAppUser
	{
	}

	protected function findOrCreate(array $event, User $user): SubscriptionAppUser
	{
	}

	protected function applyCommon(SubscriptionAppUser $subscribedUser, array $event, array $overrides)
	{
	}

	protected function carbonFromMs(?int $milliSeconds): ?Carbon
	{
		if (!$milliSeconds)
			return null;
		return Carbon::createFromTimestamp((int) ($milliSeconds / 1000));
	}

	protected function rcSubscriptionId(array $event): ?string
	{
		if (!empty($event['original_transaction_id']))
			return (string) $event['original_transaction_id'];
		if (!empty($event['transaction_id']))
			return (string) $event['transaction_id'];
		if (!empty($event['id']))
			return (string) $event['id'];
		return null;
	}

	protected function calculatePackageDuration(array $event): ?int
	{
		$purchasedMs = $event['purchased_at_ms'] ?? null;
		$expiresMs = $event['expiration_at_ms'] ?? null;

		$durationDays = 0;	// days

		if ($purchasedMs && $expiresMs) {
			$durationDays = floor(($expiresMs - $purchasedMs) / 1000 / 60 / 60 / 24);
		}
		return $durationDays;
	}
	// ----- Plan helpers -----

	protected function planFromProductId(?string $productId): ?array
	{
		return RevenueCatProductEnums::resolve($productId)?->details($productId);
	}

	protected function setTierId(User $user, array $event, ?SubscriptionAppUser $SubscriptionAppUser = null, ): void
	{
		$plan = $this->planFromProductId($event['product_id'] ?? null);

		// $this->setUserPlanFree($user);	// setting current plan free to users table

		$SubscriptionAppUser->tier_id = ($plan && $plan !== 'free') ? SubscriptionTier::productId($plan['identifier'])->first()->id : null;
		$SubscriptionAppUser->save();
	}

	protected function setUserPlanFree(User $user): void
	{
		$user->current_plan = 'free';
		$user->save();
	}
	// ----- Plan helpers -----

	protected function transactionEntry(string $flag, array $passedArray, ?SubscriptionTrx $TrxEntry = null)
	{
		// logger("a transaction entry is about to be created: {$flag}");
	}
	// -------------------- HELPERS --------------------
}
