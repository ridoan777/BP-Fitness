<?php

namespace App\Domains\Meals\Services;

use App\Domains\Meals\Services\MealExtractionService;
use App\Ai\Agents\{LargeMealGenerationAgent, MealGenerationAgent, MealSwapAgent};
use App\Helpers\Errors\{LoggerAccess, ExceptionHandling};
use App\Models\Workflows\Meals\{MealPreference, MealPlan};
use Illuminate\Support\Facades\DB;
use App\Models\Users\User;
use Exception;
use Throwable;

class SplitMealGenerateService
{
   public ?float $totalTime = 0;
   public ?int $totalInputTokens = 0;
   public ?int $totalOutputTokens = 0;
   public ?int $totalTokens = 0;
   public ?array $generatedMeals = [];

   public function __construct(
      public ?User $USER,
      public ?MealPreference $TARGET_PREFERENCE,
      public ?int $DURATION = 3,
      public ?int $CHUNK_DAY_SIZE = 7,
   ) {
   }

   public function generate()
   {
      if ($this->DURATION <= $this->CHUNK_DAY_SIZE) {
         return $this->singleCall();
      } else {
         return $this->chunkCall();
      }
   }
   // -----------------------------------------------------------

   public function swap(?MealPlan $TARGET_MEAL)
   {
      $USER = $this->USER;
      $TARGET_PREFERENCE = $this->TARGET_PREFERENCE;

      LoggerAccess::showLog(['local', 'staging'], 'info', "Meal swap from the Service.", ['duration' => $TARGET_PREFERENCE->duration]);

      // -------------- LLM CALL --------------
      $response = MealSwapAgent::make(user: $USER, TARGET_PREFERENCE: $TARGET_PREFERENCE, TARGET_MEAL: $TARGET_MEAL)->prompt("This is a part of a larger meal plan. But swap/regeneragte this with better alternative.");
      // -------------- LLM CALL --------------

      // -------------- CLASS METHODS --------------
      $content = $response->text;
      $data = json_decode($content, true);

      if (json_last_error() !== JSON_ERROR_NONE) {
         $clean = trim($content, "\" \n\r\t");
         $clean = stripslashes($clean);
         $data = json_decode($clean, true);
      }
      // -------------- CLASS METHODS --------------

      // -------------- SWAP --------------
      if (!isset($data['planning']) || !is_array($data['planning'])) {
         throw new Exception("Invalid AI response structure: missing reply/planning");
      }

      $dayKey = array_key_first($data['planning']);

      if (!$dayKey || empty($data['planning'][$dayKey]['meals'][0])) {
         throw new Exception("Invalid AI response: meal data missing");
      }

      $mealExtraction = new MealExtractionService();
      $updatedMeal = $mealExtraction->cleanSwap($data, $TARGET_MEAL);

      // -------------- SWAP --------------

      return $updatedMeal;
   }
   // -----------------------------------------------------------

   protected function singleCall()
   {
      $USER = $this->USER;
      $TARGET_PREFERENCE = $this->TARGET_PREFERENCE;

      $start = microtime(true);
      LoggerAccess::showLog(['local', 'staging'], 'info', "Meal generation for 7-day or less from Service", ['plan_duration' => $TARGET_PREFERENCE->duration]);

      // -------------- LLM CALL --------------
      $response = MealGenerationAgent::make(user: $USER, TARGET_PREFERENCE: $TARGET_PREFERENCE)->prompt("Generate a COMPLETE personalized meal plan for {$TARGET_PREFERENCE->duration} days using the user preferences.");
      // -------------- LLM CALL --------------

      $end = microtime(true);

      $timeTaken = ($start && $end) ? round($end - $start, 3) : null;

      // -------------- CLASS METHODS --------------
      $data = $this->parseResponse($response, false);

      $metaProcess = $this->metadataProcessor($response, $timeTaken);

      $TARGET_PREFERENCE->planning = $data;
      $this->parentalSaving($TARGET_PREFERENCE, $metaProcess);
      // -------------- CLASS METHODS --------------

      return $data;
   }
   // -----------------------------------------------------------

   protected function chunkCall()
   {
      $USER = $this->USER;
      $TARGET_PREFERENCE = $this->TARGET_PREFERENCE;
      $DURATION = $this->DURATION;
      $SPLIT_LIMIT = (int)$this->CHUNK_DAY_SIZE;  // e.g., only 7-days of meals will be fetched at-once

      LoggerAccess::showLog(['local', 'staging'], 'info', "Meal generation for more than 14/30 days from Service", ['plan_duration' => $DURATION]);

      LoggerAccess::showLog(['production'], 'info', "Meal generation for more than {$DURATION} days has been started.", ['user_email' => $USER->email]);
      
      $START_POINT = 1;
      $ENDING_POINT = $SPLIT_LIMIT;
      $iterations = ceil($DURATION / $SPLIT_LIMIT);
      $metaProcess = [];

      // $generatedMeals = [];

      for ($i = 1; $i <= $iterations; $i++) {

         $message = "This is a partial generation of a {$DURATION}-day meal plan. Some meals are already generated. I want you to generate the portion of the meal plan strictly respecting the {$TARGET_PREFERENCE} data ONLY days from day-{$START_POINT} to day-{$ENDING_POINT}, ensuring variety and consistency with previous meals.";

         $start = microtime(true);

         // ---------------------- LLM CALL ----------------------
         $currentChunkSize = ($ENDING_POINT - $START_POINT) + 1;
         $maxAttempts = 3;
         $attempt = 0;
         $delay = 2;
         do {
            try {
               LoggerAccess::showLog(['local', 'staging'], 'info', "-----iterations----- = ", ['iterations' => $iterations]);

               LoggerAccess::showLog(['local', 'staging'], 'info', "------------- starting|ending-------------", ['start' => $START_POINT, 'end' => $ENDING_POINT]);
               $response = LargeMealGenerationAgent::make(user: $USER, TARGET_PREFERENCE: $TARGET_PREFERENCE, TARGET_DURATION: $currentChunkSize, START_DAY: $START_POINT, END_DAY: $ENDING_POINT, generatedMeals: $this->generatedMeals)->prompt($message);

               break;
            } catch (Throwable $e) {
               $attempt++;
               if ($attempt >= $maxAttempts)
                  throw $e;

               sleep($delay);
               $delay *= 2;
            }
         } while (true);
         // ---------------------- LLM CALL ----------------------

         $end = microtime(true);
         $timeTaken = ($start && $end) ? round($end - $start, 3) : null;

         // -------------- CLASS METHODS --------------
         $data = $this->parseResponse($response, true);
         $metaProcess = $this->metadataProcessor($response, $timeTaken);

         // -------------- MERGING WITH PREVIOUS PLAN --------------
         DB::transaction(function () use ($TARGET_PREFERENCE, $data, $metaProcess) {

            $fresh = MealPreference::where('id', $TARGET_PREFERENCE->id)->lockForUpdate()->firstOrFail();

            $existingPlanning = $fresh->planning['planning'] ?? [];

            if (!is_array($existingPlanning)) {
               $existingPlanning = [];
            }

            $newPlanning = [];
            foreach (($data['planning'] ?? []) as $dayData) {
               if ($dayData['day_number']) {
                  $newPlanning['day-' . $dayData['day_number']] = $dayData;
               }
            }

            $mergedPlanning = array_merge($existingPlanning, $newPlanning);

            $fresh->planning = ['planning' => $mergedPlanning];

            $this->parentalSaving($fresh, $metaProcess);
         });
         // -------------- MERGING WITH PREVIOUS PLAN --------------

         $START_POINT = $ENDING_POINT + 1;
         if ($START_POINT > $DURATION) {
            break;
         }
         $ENDING_POINT = min($ENDING_POINT + $SPLIT_LIMIT, $DURATION);
         sleep(rand(5, 10));
      }

      $TARGET_PREFERENCE = $TARGET_PREFERENCE->fresh();
      return $TARGET_PREFERENCE->planning;
   }
   // -----------------------------------------------------------

   public function parseResponse($response, ?bool $isChunked = false)
   {
      $content = $response->text;
      $data = json_decode($content, true);
      $generatedMeals = [];

      if (json_last_error() !== JSON_ERROR_NONE) {
         $clean = trim($content, "\" \n\r\t");
         $clean = stripslashes($clean);
         $data = json_decode($clean, true);
      }

      if ($isChunked) {
         LoggerAccess::showLog(['local', 'staging'], 'info', "isChunked for generatedMeals hit", ['isChunked' => $isChunked]);
         foreach (($data['planning'] ?? []) as $dayData) {
            foreach (($dayData['meals'] ?? []) as $meal) {
               if (!empty($meal['title'])) {
                  $generatedMeals[] = $meal['title'];
               }
            }
         }
         $this->generatedMeals = array_merge($this->generatedMeals ?? [], $generatedMeals);
         $this->generatedMeals = array_slice(array_values(array_unique($this->generatedMeals)), -10);
         // $generatedMeals = array_slice(array_values(array_unique($generatedMeals)), -10);
      }

      if (!$response || json_last_error() !== JSON_ERROR_NONE || !$data) {
         ExceptionHandling::bailout(409, "The LLM process failed! Please try again later");
      }

      return $data;
   }
   // -----------------------------------------------------------

   public function parentalSaving(?MealPreference $MODEL, $metaProcess): void
   {
      if ($MODEL) {
         $MODEL->metadata = $metaProcess;
         $MODEL->ai_model = $metaProcess['default']->model ?? null;
         $MODEL->total_energy_kcal = 0;
         $MODEL->save();
      }
   }
   // -----------------------------------------------------------

   protected function metadataProcessor($response, $timeTaken): array
   {
      if (!$response) {
         return [];
      }

      $inputTokens = $response?->usage?->promptTokens ?? 0;
      $outputTokens = $response?->usage?->completionTokens ?? 0;
      $chunkTotal = $inputTokens + $outputTokens;

      $this->totalInputTokens += $inputTokens;
      $this->totalOutputTokens += $outputTokens;
      $this->totalTokens += $chunkTotal;
      $this->totalTime += $timeTaken ?? 0;

      return [
         "default" => $response?->meta ?? null,
         "tokens" => [
            "inputTokens" => $this->totalInputTokens,
            "outputTokens" => $this->totalOutputTokens,
            "total_tokens" => $this->totalTokens,
         ],
         "time_taken" => $this->totalTime,
      ];
   }

}
// Domain-structured architecture