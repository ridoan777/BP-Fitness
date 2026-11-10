<?php

namespace App\Domains\Meals\Services;

use App\Helpers\Errors\LoggerAccess;
use App\Models\Workflows\Meals\GroceryList;
use Illuminate\Support\Facades\DB;

class GroceryExtractionService
{
   // -----------------------------------------------------------

   public function extraction($TARGET_PREFERENCE)
   {
      LoggerAccess::showLog(['local', 'staging'],'info','Planning type from grocery extraction',['type' => gettype($TARGET_PREFERENCE->planning)]);
      if (!$TARGET_PREFERENCE || !$TARGET_PREFERENCE->planning) {
         return;
      }

      return DB::transaction(function () use ($TARGET_PREFERENCE) {

         $groceryMap = [];
         LoggerAccess::showLog(['local', 'staging'], 'info', "______Grocery extraction process has been started!_____");


         foreach ($TARGET_PREFERENCE->planning['planning'] as $dayData) {
            foreach ($dayData['meals'] as $meal) {
               foreach ($meal['grocery_list'] as $grocery) {

                  $name = ucfirst(str_replace('_', ' ', $grocery['item']));
                  $amount = (int)$grocery['amount'];
                  $unit = $grocery['qty_type'];

                  if (!isset($groceryMap[$name])) {
                     $groceryMap[$name] = [
                        'name' => $name,
                        'amount' => 0,
                        'unit' => $unit,
                     ];
                  }

                  $groceryMap[$name]['amount'] += $amount;
               }
            }
         }

         GroceryList::where('preference_id', $TARGET_PREFERENCE->id)->delete();

         $insertData = [];

         foreach ($groceryMap as $item) {
            $insertData[] = [
               'preference_id' => $TARGET_PREFERENCE->id,
               'meal_plan_uid' => $TARGET_PREFERENCE->preference_uid,
               'name' => $item['name'],
               'amount' => $item['amount'],
               'unit' => $item['unit'],
               'isPurchased' => 0,
               'status' => 1,
               'created_at' => now(),
               'updated_at' => now(),
            ];
         }

         if (!empty($insertData)) {
            GroceryList::insert($insertData);
         }

         return $insertData;
      });
   }
}
// Domain-structured architecture