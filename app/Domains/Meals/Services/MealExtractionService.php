<?php

namespace App\Domains\Meals\Services;

use App\Helpers\Errors\{ExceptionHandling,LoggerAccess};
use App\Helpers\UidGenerator;
use App\Models\Workflows\Meals\MealPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class MealExtractionService
{
   protected int $TOTAL_ENERGY = 0;
   public function __construct()
   {
   }
   // -----------------------------------------------------------

   public function extraction($TARGET_PREFERENCE, $data, $existingMealPlans)
   {
      if (!$TARGET_PREFERENCE || !$data || !$existingMealPlans) {
         ExceptionHandling::bailout(409, "The server failed to extract sufficient data from the LLM!");
      }

      $insertedNewData = DB::transaction(function () use ($TARGET_PREFERENCE, $data, $existingMealPlans) {

         $mealsToInsert = [];
         $this->TOTAL_ENERGY = 0;
         LoggerAccess::showLog(['local', 'staging'], 'info', "______Meal extraction process has been started!_____");


         $MEAL_UID = UidGenerator::uniqueULID("PLAN", 12, 20, 6);

         foreach ($data['planning'] as $dayKey => $dayData) {
            $dayNumber = $dayData['day_number'];
            $lastIteration = $existingMealPlans->orderByDesc('iteration_no')->value('iteration_no') ?? 0;

            foreach ($dayData['meals'] as $index => $meal) {
               $mealsToInsert[] = [
                  'meal_uid' => $MEAL_UID,
                  'preference_id' => $TARGET_PREFERENCE->id,
                  'iteration_no' => $lastIteration + 1,
                  'duration' => $TARGET_PREFERENCE->duration,
                  'day' => $dayNumber,
                  'period' => $meal['period'],
                  'title' => Str::of($meal['title'])->replace('_', ' ')->lower()->ucfirst()->toString(),
                  'image_prompt' => $meal['image_prompt'],
                  'serving_details' => $meal['serving_details'],

                  'ingredients' => array_map(function ($item) {
                     return ucfirst(str_replace('_', ' ', $item));
                  }, $meal['ingredients']),

                  'macro_nutrients' => collect($meal['macro_nutrients'])
                     ->mapWithKeys(function ($value, $key) {
                        return [ucfirst(str_replace('_', ' ', $key)) => $value];
                     })
                     ->toArray(),

                  'grocery_list' => array_map(function ($grocery) {
                     return [
                        'item' => ucfirst(str_replace('_', ' ', $grocery['item'])),
                        'qty_type' => $grocery['qty_type'],
                        'amount' => (int)$grocery['amount'],
                     ];
                  }, $meal['grocery_list']),

                  'isComplete' => 0,
                  'status' => 1,
                  'created_at' => now(),
                  'updated_at' => now(),
               ];

               $this->TOTAL_ENERGY += $meal['macro_nutrients']['energy'];
            }
         }

         if ($existingMealPlans->exists() && $existingMealPlans->count() > 0) {
            MealPlan::preferenceId($TARGET_PREFERENCE->id)->status(1)->delete();
         }

         if (!empty($mealsToInsert)) {
            foreach ($mealsToInsert as $meal) {
               MealPlan::create($meal);
            }
         }

         $TARGET_PREFERENCE->total_energy_kcal = $this->TOTAL_ENERGY;
         $TARGET_PREFERENCE->save();

         return $mealsToInsert;
      });

      return $insertedNewData;
   }

   public function cleanSwap(array $data, MealPlan $TARGET_MEAL)
   {
      if (!isset($data['planning']) || !is_array($data['planning'])) {
         ExceptionHandling::bailout(409, "Invalid AI response structure!");
      }

      $dayKey = array_key_first($data['planning']);

      if (!$dayKey || empty($data['planning'][$dayKey]['meals'][0])) {
         ExceptionHandling::bailout(409, "Invalid AI response: meal missing!");
      }

      $meal = $data['planning'][$dayKey]['meals'][0];

      return DB::transaction(function () use ($meal, $TARGET_MEAL) {

         $cleaned = [
            'title' => Str::of($meal['title'])->replace('_', ' ')->lower()->ucfirst()->toString(),
            'image_prompt' => $meal['image_prompt'],
            'serving_details' => $meal['serving_details'],

            'ingredients' => array_map(fn($item) => ucfirst(str_replace('_', ' ', $item)), $meal['ingredients']),

            'macro_nutrients' => collect($meal['macro_nutrients'])
               ->mapWithKeys(fn($value, $key) => [ucfirst(str_replace('_', ' ', $key)) => $value])
               ->toArray(),

            'grocery_list' => array_map(function ($grocery) {
               return [
                  'item' => ucfirst(str_replace('_', ' ', $grocery['item'])),
                  'qty_type' => $grocery['qty_type'],
                  'amount' => (int)$grocery['amount'],
               ];
            }, $meal['grocery_list']),
         ];

         $TARGET_MEAL->update($cleaned);

         return $TARGET_MEAL->fresh();
      });
   }

}
// Domain-structured architecture