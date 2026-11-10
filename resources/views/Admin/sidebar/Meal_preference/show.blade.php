<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Preference List', 'url' => 'backend_preference_index', 'icon' => 'carrot'],
			['label' => 'Single Preference'],
		];
	@endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Preference: {{ $preference?->preference_uid ?? "N/A" }}" />
	<!-- End Main Widgets -->

	<!-- Alert Component -->
	<x-indicators.alert-component message="Modification" />
	<!-- Alert Component -->

	@php
		$ACCORDION_1_VISIBILITY = 'hidden'; // hidden or ''
		$ACCORDION_2_VISIBILITY = 'hidden';
		$ACCORDION_3_VISIBILITY = '';
		$ACCORDION_4_VISIBILITY = 'hidden';
	@endphp
	<!-- ---------------- APP AREA Starts ---------------- -->

	<div id="hs-destroy-heading-one"
		class="hs-accordion-to-destroy hs-accordion hs-accordion-active:border-gray-200 PrelineAccordionItemParent">

		<x-ui_items.accordion.accordion_toggle_button toggleLabel="Meal Preference" />

		<div id="hs-destroy-collapse-one"
			class="hs-accordion-content {{ $ACCORDION_1_VISIBILITY }} w-full overflow-hidden transition-[height] duration-300"
			role="region" aria-labelledby="hs-destroy-heading-one">
			<div class="p-3">
				<!-- --------------------- -->
				<section
					class="displayArea mx-auto my-5 rounded-lg border-none bg-gray-200 p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
					<div class="p-6">

						<div class="rounded-lg dark:bg-neutral-700">

							<!-- ----------- USER INFO ----------- -->
							<div class="my-4 bg-gray-100 dark:bg-neutral-700 shadow-lg rounded-lg p-6 space-y-4">
								<h3 class="mb-8">USER INFORMATION</h3>

								<div class="grid grid-cols-4 gap-8">
									<div class="col-span-1">
										@php
											$getAvatar = $user ? $user?->avatar : null;
											if ($getAvatar) {
												$avatar = Storage::url($getAvatar);
											} else {
												$avatar = asset('site_assets/dummies/dummy_man.webp');
											}
										 @endphp
										<a href="{{ route('backend_single_user', $user?->id) }}" target="_blank" rel="noopener">
											<img src="{{ $avatar }}" alt="{{ $getAvatar ?? 'no-avatar' }}"
												class="rounded-full cursor-pointer hover:scale-105">
										</a>
									</div>
									<div class="col-span-3 space-y-4">
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">DB ID:</P>
											<p class="filledParagraph col-span-9">{{ $user?->id ?? "N/A" }}</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">DB UID:</P>
											<p class="filledParagraph col-span-9">{{ $user?->user_uid ?? "N/A" }}</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Username:</P>
											<p class="filledParagraph col-span-9">{{ $user?->username ?? "N/A" }}</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">User Name:</P>
											<p class="filledParagraph col-span-9">{{ $user?->name ?? "N/A" }}</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Email:</P>
											<p class="filledParagraph col-span-9">{{ $user?->email ?? "N/A" }}</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Gender:</P>
											<p class="filledParagraph col-span-9">{{ $user?->gender ?? "N/A" }}</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Age:</P>
											<p class="filledParagraph col-span-9">{{ $user?->age ?? "N/A" }}</p>
										</div>
										<!----->
									</div>
								</div>

							</div>
							<!-- ----------- USER INFO ----------- -->

							<!-- ----------- PREFERENCE INFO ----------- -->
							<div class="my-4 bg-gray-100 dark:bg-neutral-700 shadow-lg rounded-lg p-6 space-y-4">
								<h3 class="mb-8">PREFERENCE INFORMATION</h3>

								<div class="grid grid-cols-12 gap-8">
									<div class="col-span-12 space-y-4">
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">DB ID:</P>
											<P class="filledParagraph col-span-2">{{ $preference?->id ?? "N/A" }}</p>
											<P class="filledParagraph col-span-3 font-bold">DB UID:</P>
											<P class="filledParagraph col-span-4">{{ $preference?->preference_uid ?? "N/A" }}</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Duration:</P>
											<P class="filledParagraph col-span-2">
												<span class="py-2 px-4 bg-rose-200 dark:text-black rounded-full">{{
													$preference?->duration ? $preference?->duration . " days" :
													"N/A" }}</span>
											</p>
											<P class="filledParagraph col-span-3 font-bold">Goal:</P>
											<P class="filledParagraph col-span-4">{{ $preference?->goal ? ucfirst(str_replace('_','
												',$preference?->goal)) : "N/A" }}</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Gender:</P>
											<P class="filledParagraph col-span-3">{{ $preference?->gender ?? "N/A" }}</p>
											<P class="filledParagraph col-span-3 font-bold">Age:</P>
											<P class="filledParagraph col-span-3">{{ $preference?->age ?? "N/A" }}</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Height:</p>
											<P class="filledParagraph col-span-3">
												{{ $preference?->height_cm
												? $preference->height_cm.' cm | '.
												floor(($preference->height_cm / 2.54) / 12).' ft '.
												round(($preference->height_cm / 2.54) % 12).' in'
												: 'N/A' }}
											</p>

											<P class="filledParagraph col-span-3 font-bold">Weight:</p>
											<P class="filledParagraph col-span-3">
												{{ $preference?->weight_kg
												? $preference->weight_kg.' kg | '.round($preference->weight_kg * 2.20462, 1).' lb'
												: 'N/A' }}
											</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Workout:</P>
											<P class="filledParagraph col-span-3">{{ $preference?->workout ? $preference?->workout
												. " day(s)" :
												"N/A" }}</p>
											<P class="filledParagraph col-span-3 font-bold">Sleep:</P>
											<P class="filledParagraph col-span-3">{{ $preference?->sleep ? $preference?->sleep . "
												hour(s)" : "N/A"
												}}</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Prefered Exercises:</P>
											<p class="filledParagraph col-span-9 flex flex-wrap gap-2">
												@php $items = $preference?->prefer_exercise; @endphp

												@if(is_array($items) && count($items))
												@foreach($items as $item)
												<span class="px-2 py-1 text-xs rounded-full bg-blue-200 text-blue-700">
													{{ ucfirst(str_replace('_',' ',$item)) }}
												</span>
												@endforeach
												@elseif($items)
												<span class="px-2 py-1 text-xs rounded-full bg-blue-200 text-blue-700">
													{{ ucfirst(str_replace('_',' ',$items)) }}
												</span>
												@else
												<span class="text-gray-400">N/A</span>
												@endif
											</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Preferred Nutritions:</P>
											<p class="filledParagraph col-span-9 flex flex-wrap gap-2">
												@php $items = $preference?->nutrition; @endphp

												@if(is_array($items) && count($items))
												@foreach($items as $item)
												<span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-700">
													{{ ucfirst(str_replace('_',' ',$item)) }}
												</span>
												@endforeach
												@elseif($items)
												<span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-700">
													{{ ucfirst(str_replace('_',' ',$items)) }}
												</span>
												@else
												<span class="text-gray-400">N/A</span>
												@endif
											</p>
										</div>
										<!----->
										<div class="grid grid-cols-12 gap-4">
											<P class="filledParagraph col-span-3 font-bold">Medical Conditions:</P>
											<p class="filledParagraph col-span-9 flex flex-wrap gap-2">
												@php $items = $preference?->medical; @endphp

												@if(is_array($items) && count($items))
												@foreach($items as $item)
												<span class="px-2 py-1 text-xs rounded-full bg-red-200 text-red-700">
													{{ ucfirst(str_replace('_',' ',$item)) }}
												</span>
												@endforeach
												@elseif($items)
												<span class="px-2 py-1 text-xs rounded-full bg-red-200 text-red-700">
													{{ ucfirst(str_replace('_',' ',$items)) }}
												</span>
												@else
												<span class="text-gray-400">N/A</span>
												@endif
											</p>
										</div>
										<!----->
									</div>
								</div>

							</div>
							<!-- ----------- PREFERENCE INFO ----------- -->

						</div>

					</div>
				</section>
			</div>
		</div>
	</div>
	<!------------->

	<div id="hs-destroy-heading-two"
		class="hs-accordion-to-destroy hs-accordion hs-accordion-active:border-gray-200 PrelineAccordionItemParent">

		<x-ui_items.accordion.accordion_toggle_button toggleLabel="LML Information (AI)" />

		<div id="hs-destroy-collapse-two"
			class="hs-accordion-content {{ $ACCORDION_2_VISIBILITY }} w-full overflow-hidden transition-[height] duration-300"
			role="region" aria-labelledby="hs-destroy-heading-two">
			<div class="p-3">
				<!-- --------------------- -->
				<section
					class="displayArea mx-auto my-5 rounded-lg border-none bg-gray-200 p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
					<div class="p-6">

						<div class="rounded-lg dark:bg-neutral-700">


							<!-- ----------- PREFERENCE INFO ----------- -->
							<div class="my-4 bg-gray-100 dark:bg-neutral-700 shadow-lg rounded-lg p-6 space-y-4">
								<h3 class="mb-8">AI MEATADATA</h3>

								<div class="grid grid-cols-12 gap-4">

									<!----- Tokens ----->
									<div class="col-span-12 bg-gray-50 dark:bg-neutral-600 p-4 rounded-lg shadow-sm">
										<div class="grid grid-cols-4 gap-2 items-center text-sm">
											<h6 class="font-semibold">Tokens: </h6>
											<div>
												<span class="py-2 px-4 bg-violet-200 rounded-full">
													<span class="font-medium">Input:</span>
													{{ $preference->metadata['tokens']['inputTokens'] ?? 'N/A' }}
												</span>
											</div>
											<div>
												<span class="py-2 px-4 bg-violet-200 rounded-full">
													<span class="font-medium">Output:</span>
													{{ $preference->metadata['tokens']['outputTokens'] ?? 'N/A' }}
												</span>
											</div>
											<div>
												<span class="py-2 px-4 bg-violet-200 rounded-full">
													<span class="font-medium">Total:</span>
													{{ $preference->metadata['tokens']['total_tokens'] ?? 'N/A' }}
												</span>
											</div>
										</div>
									</div>
									<!----- Tokens ----->

									<!----- Model ----->
									<div class="col-span-12 bg-gray-50 dark:bg-neutral-600 p-4 rounded-lg shadow-sm">
										<div class="grid grid-cols-4 gap-2 items-center text-sm">
											<h6 class="font-semibold">Model Info: </h6>
											
											@php 
												$provider = data_get($preference->metadata, 'default.provider'); 
												$model = data_get($preference->metadata, 'default.model'); 
											@endphp

											<div>
												<span class="py-2 px-4 bg-orange-200 rounded-full">
													<span class="font-medium">Model:</span>
													{{ $model ? ucfirst($model) : 'N/A' }}
												</span>
											</div>
											<div>
												<span class="py-2 px-4 bg-orange-200 rounded-full">
													<span class="font-medium">LLM Provider:</span>
													{{ $provider ? ucfirst($provider) : 'N/A' }}
												</span>
											</div>
											<div>
												<span class="py-2 px-4 bg-orange-200 rounded-full">
													<span class="font-medium">Citations:</span>
													{{ !empty($preference->metadata['default']['citations']) ? implode(', ',
													$preference->metadata['default']['citations']) : 'None' }}
												</span>
											</div>
										</div>
									</div>
									<!----- Model ----->

									<!----- Performance ----->
									<div class="col-span-12 bg-gray-50 dark:bg-neutral-600 p-4 rounded-lg shadow-sm">
										<div class="grid grid-cols-4 gap-2 items-center text-sm">
											<h6 class="font-semibold">Performance: </h6>
											<div>
												<span class="py-2 px-4 bg-emerald-200 rounded-full">
													<span class="font-medium">Time Taken for generation:</span>
													{{ isset($preference->metadata['time_taken']) ?
													round($preference->metadata['time_taken'], 2) . ' sec' : 'N/A' }}
												</span>
											</div>
										</div>
									</div>
									<!----- Performance ----->

								</div>

							</div>
							<!-- ----------- PREFERENCE INFO ----------- -->

						</div>

					</div>
				</section>
			</div>
		</div>
	</div>
	<!------------->

	<div id="hs-destroy-heading-three"
		class="hs-accordion-to-destroy hs-accordion hs-accordion-active:border-gray-200 PrelineAccordionItemParent">

		<x-ui_items.accordion.accordion_toggle_button toggleLabel="Generated Plan" />

		<div id="hs-destroy-collapse-three"
			class="hs-accordion-content {{ $ACCORDION_3_VISIBILITY }} w-full overflow-hidden transition-[height] duration-300"
			role="region" aria-labelledby="hs-destroy-heading-three">
			<div class="p-3">
				<!-- --------------------- -->
				<section
					class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
					<div class="p-6">

						<div class="rounded-lg p-4 dark:bg-neutral-700">

							<!----- MEAL PLAN ----->
							@if($mealPlan && $mealPlan->isNotEmpty())
								<div class="mb-6">
									<h2 class="text-2xl font-semibold mb-4">Meal Plan</h2>
									<div class="w-fill my-4 grid grid-cols-12 gap-4 items-center">
										<p class="filledParagraph col-span-2 font-bold">Meal UID: </p>
										<p class="filledParagraph col-span-4">{{ optional($mealPlan->first())->meal_uid ?? 'N/A' }}</p>
										<p class="filledParagraph col-span-3 font-bold">Total Generation(s): </p>
										<p class="filledParagraph col-span-3">{{ optional($mealPlan->first())->iteration_no ?? 'N/A' }}</p>
									</div>

									<div class="w-full grid grid-cols-3 gap-4">
										@foreach ($mealPlan as $index => $plan)
											<div
												class="col-span-1 mb-5 border flex flex-col justify-between border-gray-300 dark:border-gray-600 dark:bg-neutral-600 rounded-lg p-4 hover:shadow-lg cursor-pointer">
												<div>

													<p class="flex_between_center">
														<span class="">
															<span title="Completed" class="{{ $plan?->isComplete ? 'p-1 bg-green-500 rounded-full' : '' }} text-gray-200">✔</span>
															<span
																class="p-2 rounded-full bg-orange-200 text-2xs">Day:{{ $plan?->day ?? "N/A" }}</span>
														</span>
														<span
															class="p-2 rounded-full bg-violet-200 text-2xs">{{ $plan?->period ?? "N/A" }}</span>
													</p>
													<!----->
													<div class="mt-8 appText text-sm italic">
														<span class="font-medium">DB ID:</span>
														<span class="text-sm">{{ $plan?->id ?? "N/A" }}</span>
													</div>
													<!----->
													<div class="my-2 appText">
														<span class="font-bold">Food:</span>
														<span class="text-sm">{{ $plan?->title ?? "N/A" }}</span>
													</div>
													<!----->
													<div class="my-2 appText">
														<span class="font-bold">Serving Details:</span>
														<span class="text-sm italic text-gray-400">{{ $plan?->serving_details ?? "N/A" }}</span>
													</div>
													<!----->
													<div class="my-2 appText">
														<span class="font-bold">Ingredients:</span>
														@if(is_array($plan->ingredients) && count($plan->ingredients))
		
															@foreach($plan->ingredients as $item)
																<span class="mx-1 px-2 py-1 text-xs rounded-full bg-cyan-100 text-cyan-700">
																	{{ $item }}
																</span>
															@endforeach
														@else
															<span class="text-gray-400">N/A</span>
														@endif
													</div>
													<!----->
													<div class="my-2 appText">
														<span class="font-bold">Macro Nutrients:</span>
														@if(is_array($plan->macro_nutrients) && count($plan->macro_nutrients))
		
															@foreach($plan->macro_nutrients as $key => $value)
																<span class="mx-1 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
																	{{ $key }}: {{ number_format($value) }}g
																</span>
															@endforeach
		
														@else
															<span class="text-gray-400">N/A</span>
														@endif
													</div>
												</div>
												<!----->
												<div class="mt-8 mb-2 appText">
													<span class="font-bold">Last Swapped:</span>
													<span class="text-sm">{{ ($plan?->created_at?->timestamp !== $plan?->updated_at?->timestamp) ? $plan?->updated_at->format('d M Y, H:i') : "N/A" }}</span>
												</div>

											</div>
										@endforeach
									</div>
								</div>
							@else
								<div class="mb-6">
									<h4 class="text-center text-gray-500">No meal has been generated yet!</h4>
								</div>
							@endif

						</div>

					</div>
				</section>
			</div>
		</div>
	</div>
	<!------------->

	<div id="hs-destroy-heading-four"
		class="hs-accordion-to-destroy hs-accordion hs-accordion-active:border-gray-200 PrelineAccordionItemParent">

		<x-ui_items.accordion.accordion_toggle_button toggleLabel="Grocery Lists" />

		<div id="hs-destroy-collapse-four"
			class="hs-accordion-content {{ $ACCORDION_4_VISIBILITY }} w-full overflow-hidden transition-[height] duration-300"
			role="region" aria-labelledby="hs-destroy-heading-four">
			<div class="p-3">
				<!-- --------------------- -->
				<section
					class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
					<div class="p-6">

						<div class="rounded-lg p-4 dark:bg-neutral-700">

							<!----- GROCERY LIST ----->
							@if($groceryList && $groceryList->isNotEmpty())
								<div class="mb-6">
									<h2 class="text-2xl font-semibold mb-4">GROCERY LIST</h2>

									<div class="w-full grid grid-cols-4 gap-4">
										@foreach ($groceryList as $index => $grocery)
											<div
												class="col-span-1 mb-5 border flex flex-col justify-between border-gray-300 dark:border-gray-600 dark:bg-neutral-600 rounded-lg p-4 hover:shadow-lg cursor-pointer">
												<div>

													<p class="flex_between_center">
														<span class="">
															<span
																class="p-2 rounded-full bg-rose-200 text-2xs">Item:{{ $index+1 }}</span>
														</span>
														<span class="p-2 rounded-full text-2xs">
															<span title="Completed" class="{{ $grocery?->isPurchased ? 'p-2 bg-violet-500 rounded-full' : '' }} text-gray-200">✔</span>
														</span>
													</p>
													<!----->
													<div class="mt-4 appText text-sm italic flex_between_center">
														<span class="font-medium">DB ID:
															<span class="text-sm">{{ $grocery?->id ?? "N/A" }}</span>
														</span>
														<img src="{{ $grocery?->image }}" alt="{{ $grocery?->image }}" class="h-12 w-12 hover:scale-110">
													</div>
													<!----->
													<div class="my-2 appText">
														<span class="font-bold">Name:</span>
														<span class="text-base">{{ $grocery?->name ?? "N/A" }}</span>
													</div>
													<!----->
													<div class="my-2 appText">
														<span class="font-bold">Amount:</span>
														<span class="text-sm italic text-gray-400">{{ $grocery?->amount ?? null }} {{ $grocery?->unit ? $grocery?->unit . " (s)" : null }}</span>
													</div>
													<!----->
												</div>

											</div>
										@endforeach
									</div>
								</div>
							@else
								<div class="mb-6">
									<h4 class="text-center text-gray-500">No grocery list has been generated yet! Try generating a meal plan first.</h4>
								</div>
							@endif

						</div>

					</div>
				</section>
			</div>
		</div>
	</div>
	<!------------->


	<!-- ---------------- APP AREA Starts ---------------- -->

</x-app-layout>

<style>
	.filledParagraph {
		padding: 16px;
		border-radius: 8px;
		background-color: #e5e7eb;
	}

	.dark .filledParagraph {
		color: #e5e7eb;
		background-color: #737373;
	}
</style>