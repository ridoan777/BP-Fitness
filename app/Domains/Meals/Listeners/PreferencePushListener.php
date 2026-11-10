<?php

namespace App\Domains\Meals\Listeners;

use App\Domains\Meals\Events\PreferenceNotifyEvents;
use App\Enums\NotifyChannelEnums;
use App\Helpers\Notifications\PushNotificationHelper;
use App\Notifications\PushNotifications\MealPreferencePushNotify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PreferencePushListener
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
        if (!in_array(NotifyChannelEnums::PUSH, $event->channels)) {
            return;
        }

        $forced = in_array(NotifyChannelEnums::PUSH, $event->force);

        PushNotificationHelper::selectedUsersPush(new MealPreferencePushNotify(false, $event->targetModel, $event->actor, $event->actionFlag), $event->targetReceivers, $forced);
    }
}