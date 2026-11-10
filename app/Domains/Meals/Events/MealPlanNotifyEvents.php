<?php

namespace App\Domains\Meals\Events;

use App\Domains\Meals\Enums\MealPlanPhaseEnums;
use App\Enums\NotifyChannelEnums;
use App\Models\Users\User;
use App\Models\Workflows\Meals\MealPreference;
use Illuminate\Broadcasting\{Channel, InteractsWithSockets, PrivateChannel};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MealPlanNotifyEvents
{
	use Dispatchable, InteractsWithSockets, SerializesModels;
	
	public function __construct(
		public ?MealPreference $targetModel = null,
		public ?array $targetReceivers = [],
		public User $actor,
		public MealPlanPhaseEnums $actionFlag,
		public array $channels = [NotifyChannelEnums::PUSH, NotifyChannelEnums::IN_APP],
		public array $force = [] // [NotifyChannelEnums::PUSH, NotifyChannelEnums::IN_APP]
	) {
		// logger('Preference notify events has been reached');
	}
}

// -------------------- NOTIFICATION EVENTS --------------------
// $actionFlag = $isNewPlan ? PreferenceCRUDEnums::CREATED : PreferenceCRUDEnums::UPDATED;
// event(new PreferenceNotifyEvents($mealPreference, [$USER], $USER, $actionFlag, [NotifyChannelEnums::PUSH, NotifyChannelEnums::IN_APP], [NotifyChannelEnums::IN_APP]));
// -------------------- NOTIFICATION EVENTS --------------------