<?php

namespace App\Domains\Billings\Subscriptions\Controllers\Tiers;

use App\Domains\Billings\Subscriptions\Actions\SubscriptionTierSaveAction;
use App\Domains\Billings\Subscriptions\Models\SubscriptionTier;
use App\Domains\Billings\Subscriptions\Requests\TierValidationRequest;
use App\Helpers\{FileManagement, Notifications\InAppNotificationHelper, Notifications\PushNotificationHelper, UidGenerator};
use App\Http\Controllers\Controller;
use App\Models\System\Settings\OptionSiteSetup;
use App\Models\Workflows\Projects\Project;
use Exception;
use Throwable;

class WebSubscriptionTierController extends Controller
{
	# Due to proprietary secrecy, code that contained business rules were removed
	public function index()
	{
	}

	public function show(SubscriptionTier $tier)
	{
	}

	public function store(TierValidationRequest $request, SubscriptionTierSaveAction $action)
	{
	}

	public function delete(SubscriptionTier $tier)
	{
	}
}
