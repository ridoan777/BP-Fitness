<?php

namespace App\Jobs;

use App\Domains\Meals\Enums\MealPlanPhaseEnums;
use App\Domains\Meals\Events\MealPlanNotifyEvents;
use App\Domains\Meals\Services\{GroceryExtractionService, SplitMealGenerateService, MealExtractionService};
use App\Enums\NotifyChannelEnums;
use App\Helpers\Errors\LoggerAccess;
use App\Helpers\Notifications\PushNotificationHelper;
use App\Models\Users\User;
use App\Models\Workflows\Meals\{MealPreference, MealPlan};
use App\Notifications\PushNotifications\MealPlanPushNotify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\{Tries, Timeout};
use Throwable;


// #[Connection('redis')]
// #[Queue('podcasts')]
#[Tries(3)]
#[Timeout(360)]
class GenerateMealJob implements ShouldQueue
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $userId,
        public int $preferenceId
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $USER = User::findOrFail($this->userId);
        $TARGET_PREFERENCE = MealPreference::findOrFail($this->preferenceId);

        // ----------------------------- NOTIFICATION EVENTS -----------------------------
        event(new MealPlanNotifyEvents($TARGET_PREFERENCE, [$USER], $USER, MealPlanPhaseEnums::IN_PROGRESS, [NotifyChannelEnums::PUSH, NotifyChannelEnums::IN_APP], [NotifyChannelEnums::IN_APP, NotifyChannelEnums::PUSH]));
        try {
            set_time_limit(0);
            LoggerAccess::showLog(['local', 'staging'], 'info', "Meal generation job queue for 7-days or less has started!");

            $DURATION = $TARGET_PREFERENCE->duration ?? 3;

            // ------------------ GENERATION ------------------
            $service = new SplitMealGenerateService(USER: $USER, TARGET_PREFERENCE: $TARGET_PREFERENCE, DURATION: $DURATION, CHUNK_DAY_SIZE: 7);

            $data = $service->generate();

            // ------------------ MEAL EXTRACTION ------------------
            $existingMealPlans = MealPlan::preferenceId($TARGET_PREFERENCE->id)->status(1);

            $mealExtraction = new MealExtractionService();
            $mealExtraction->extraction($TARGET_PREFERENCE, $data, $existingMealPlans);

            // ------------------ GROCERY EXTRACTION ------------------
            $TARGET_PREFERENCE = $TARGET_PREFERENCE->fresh();
            $groceryService = new GroceryExtractionService();
            $groceryService->extraction($TARGET_PREFERENCE);

            // ------------------ FINAL STATE ------------------
            $TARGET_PREFERENCE->update(['phase' => 'completed']);

            LoggerAccess::showLog(['local', 'staging'], 'info', "______Meal generation completed!_____", ['preference_id' => $this->preferenceId, 'user' => $USER?->name ?? null]);

            // ----------------------------- NOTIFICATION EVENTS -----------------------------

            try {
                event(new MealPlanNotifyEvents($TARGET_PREFERENCE, [$USER], $USER, MealPlanPhaseEnums::COMPLETED, [NotifyChannelEnums::PUSH, NotifyChannelEnums::IN_APP], [NotifyChannelEnums::IN_APP, NotifyChannelEnums::PUSH]));
            } catch (Throwable $e) {
                LoggerAccess::showLog(['local', 'staging'], 'error', 'Meal completion notification failed', ['error' => $e->getMessage()]);
            }

        } catch (Throwable $e) {

            $TARGET_PREFERENCE->update(['phase' => 'failed']);

            // ----------------------------- NOTIFICATION EVENTS -----------------------------
            event(new MealPlanNotifyEvents($TARGET_PREFERENCE, [$USER], $USER, MealPlanPhaseEnums::CANCELLED, [NotifyChannelEnums::PUSH, NotifyChannelEnums::IN_APP], [NotifyChannelEnums::IN_APP, NotifyChannelEnums::PUSH]));

            LoggerAccess::showLog(['local', 'staging'], 'error', 'Meal job failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }

    }
}
/*
CROP SETUP:
    /usr/bin/php /home/u228557888/domains/thebpfitnessapp.com/public_html/laravel/artisan queue:work --stop-when-empty --tries=3 --timeout=1200

*/