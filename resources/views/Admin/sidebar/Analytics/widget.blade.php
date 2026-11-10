@php
   $totalUsers = \App\Models\Users\User::whereNotNull('email_verified_at')->status(1)->count();
   $totalItems = \App\Domains\E_Store\Products\Models\ProductItem::status(1)->count();
   $totalCourses = \App\Domains\Workout_courses\Models\WorkoutCourse::status(1)->count();
   $totalMealPlan = \App\Models\Workflows\Meals\MealPreference::status(1)->whereNotNull('planning')->whereHas('mealPreferenceRelationWith_Plan')->count();
   $totalOrders = \App\Domains\E_Store\Shoppings\Models\ProductOrder::status(1)->count();
   $totalUnpaidOrder = \App\Domains\E_Store\Shoppings\Models\ProductOrder::status(1)->paymentStatus(\App\Domains\Billings\Shared\Enums\PaymentStatusEnums::UNPAID)->count();
   $totalCompletedOrder = \App\Domains\E_Store\Shoppings\Models\ProductOrder::status(1)->orderStatus(\App\Domains\E_Store\Shoppings\Enums\OrderStatusEnums::COMPLETED)->count();
   $totalRevenue = \App\Domains\E_Store\Shoppings\Models\ProductOrder::status(1)->sum('paid_amount');

   $widgets = [
      ['title' => 'Total Users', 'value' => $totalUsers ?? 0, 'color' => 'hover:bg-violet-500'],
      ['title' => 'Total Sellable Products', 'value' => $totalItems ?? 0, 'color' => 'hover:bg-sky-500'],
      ['title' => 'Total Workout Courses', 'value' => $totalCourses ?? 0, 'color' => 'hover:bg-amber-500'],
      ['title' => 'Totals Meal Plans', 'value' => $totalMealPlan ?? 0, 'color' => 'hover:bg-emerald-500'],
      ['title' => 'Total Orders', 'value' => $totalOrders ?? 0, 'color' => 'hover:bg-rose-500'],
      ['title' => 'Total Unpaid Order', 'value' => $totalUnpaidOrder ?? 0, 'color' => 'hover:bg-red-500'],
      ['title' => 'Total Completed Order', 'value' => $totalCompletedOrder ?? 0, 'color' => 'hover:bg-teal-500'],
      ['title' => 'Total Revenue (all currencies)', 'value' => number_format($totalRevenue ?? 0), 'color' => 'hover:bg-lime-500'],
   ];
@endphp

@foreach ($widgets as $widget)
   <div
      class="p-2 rounded-lg grid items-center bg-gray-700 {{ $widget['color'] }} dark:bg-gray-800 text-center hover:cursor-pointer">
      <h4 class="text-white">{{ $widget['title'] }}</h4>
      <h1 class="text-white">{{ $widget['value'] }}</h1>
   </div>
   <!----->
@endforeach
