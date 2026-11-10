<?php

namespace App\Domains\Meals\Listeners;

use App\Domains\Meals\Events\MealPlanNotifyEvents;
use App\Enums\NotifyChannelEnums;
use App\Notifications\InAppNotifications\MealPlanInAppNotify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MealPlanInAppListener
{
    // called from App\Domains\Users\Events\ApiAuthNotifyEvent
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MealPlanNotifyEvents $event): void
    
    {
        if (!in_array(NotifyChannelEnums::IN_APP, $event->channels)) {
            return;
        }

        $forced = in_array(NotifyChannelEnums::IN_APP, $event->force);
        $notifier = new MealPlanInAppNotify($event->targetModel, $event->actor, $forced, $event->targetReceivers);

        $notifier->handle($event->actionFlag);
    }
}