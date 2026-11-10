<?php

namespace App\Domains\Billings\Subscriptions\Controllers\Tiers;

use App\Helpers\ApiJsonReturnHelper;
use App\Helpers\Errors\ExceptionHandling;
use App\Http\Controllers\Controller;
use App\Domains\Billings\Subscriptions\Models\SubscriptionTier;
use Illuminate\Http\Request;
use Throwable;

class ApiSubscriptionTierController extends Controller
{
	public function indexWeb()
	{
		try {
			$webTiers = SubscriptionTier::platform('web')->status(1)->get();
			return ApiJsonReturnHelper::handle(true, 200, 'All subscription tiers have been fetched.', $webTiers);
		} catch (Throwable $e) {
			return ExceptionHandling::handle('fetching subscription tiers for web', $e);
		}
	}

	public function indexMobile()
	{
		try {
			$mobileTiers = SubscriptionTier::platform('mobile')->status(1)->get();
			return ApiJsonReturnHelper::handle(true, 200, 'All subscription tiers have been fetched.', $mobileTiers);
		} catch (Throwable $e) {
			return ExceptionHandling::handle('fetching subscription tiers for mobile', $e);
		}
	}
}