<?php

namespace App\Jobs;

use App\Domains\Meals\Enums\PreferenceCRUDEnums;
use App\Domains\Meals\Events\PreferenceNotifyEvents;
use App\Enums\NotifyChannelEnums;
use App\Helpers\Errors\LoggerAccess;
use App\Models\Users\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\{Tries, Timeout};


// #[Connection('redis')]
// #[Queue('podcasts')]
#[Tries(3)]
#[Timeout(120)]
class NotificationJob implements ShouldQueue
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $RECEIVERS,
        public ?User $FOCUS_USER = null,
        public $targetModel = null,
        public ?bool $isNewPlan = false,
    ) {
        $this->RECEIVERS = collect($RECEIVERS)->map(fn($u) => $u instanceof User ? $u->id : $u)->all();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        set_time_limit(0);
        $receivers = User::whereIn('id', $this->RECEIVERS)->get()->all();

        $actionFlag = $this->isNewPlan ? PreferenceCRUDEnums::CREATED : PreferenceCRUDEnums::UPDATED;

        event(new PreferenceNotifyEvents($this->targetModel, $receivers, $this->FOCUS_USER, $actionFlag, [NotifyChannelEnums::PUSH, NotifyChannelEnums::IN_APP], [NotifyChannelEnums::IN_APP, NotifyChannelEnums::PUSH]));
    }
}

/*
NotificationJob::dispatch(RECEIVERS: [$USER], FOCUS_USER: $USER, targetModel: $mealPreference, isNewPlan: $isNewPlan);

*/