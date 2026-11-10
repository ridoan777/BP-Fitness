<?php

namespace App\Domains\Meals\Listeners;

use App\Domains\Meals\Events\PreferenceNotifyEvents;
use App\Enums\NotifyChannelEnums;
use App\Notifications\InAppNotifications\MealPreferenceInAppNotify;
use App\Notifications\PushNotifications\ApiAuthPushNotify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PreferenceInAppListener
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
    public function handle(PreferenceNotifyEvents $event): void
    {
        if (!in_array(NotifyChannelEnums::IN_APP, $event->channels)) {
            return;
        }

        $forced = in_array(NotifyChannelEnums::IN_APP, $event->force);
        $notifier = new MealPreferenceInAppNotify($event->targetModel, $event->actor, $forced, $event->targetReceivers);

        $notifier->handle($event->actionFlag);
    }
}