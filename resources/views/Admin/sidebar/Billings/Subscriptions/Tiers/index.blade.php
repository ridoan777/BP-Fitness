<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Tiers', 'icon' => 'dollarSack'],
			['label' => 'Customers', 'url' => 'backend_billings_customers_index', 'icon' => 'userTrippleLine'],
			['label' => 'Transactions', 'url' => 'backend_transactions_index', 'icon' => 'dollarSack'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Subscriptions: Tiers/Packages" />
	<!-- End Main Widgets -->

	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
		$ACCORDION_2_VISIBILITY = '';
		$ACCORDION_3_VISIBILITY = '';
	@endphp

	<!-- -------------------------------- APP AREA Starts -------------------------------- -->
	<div class="hs-accordion-group">

		<x-ui_items.accordion.accordion-destroy />

		<div id="hs-destroy-heading-one"
			class="hs-accordion-to-destroy hs-accordion hs-accordion-active:border-gray-200 PrelineAccordionItemParent">

			<x-ui_items.accordion.accordion_toggle_button toggleLabel="Create New Tiers" />

			<!-- --------------------------------- -->
			<div id="hs-destroy-collapse-one"
				class="hs-accordion-content {{ $ACCORDION_1_VISIBILITY }} w-full overflow-hidden transition-[height] duration-300"
				role="region" aria-labelledby="hs-destroy-heading-one">
				<div class="p-3">
					<form method="POST" action="{{ route('backend_subscription_tier_store') }}"
						class="rounded-lg p-2 dark:bg-neutral-700" enctype="multipart/form-data">
						@csrf

						<!--- IDENTIFIER HIDDEN INPUTS -->
						<input type="hidden" name="flag" class="smallInput" value="create">
						<!--- IDENTIFIER HIDDEN INPUTS -->
						<div>

							<div class="grid grid-cols-1 gap-4">
								<div class="mb-5 mt-2">
									<label for="platform" class="inputLabelMedium">Subscription Platform
										<span class="text-red-500">*</span>
									</label>
									<select id="plan" name="platform" class="dropdownSelectInputs">
										<option value="mobile">Mobile (Apple/Google Stores)</option>
										{{-- <option value="web" disabled>Web (Stripe, etc.)</option> --}}
									</select>
								</div>
							</div>
							<!-- -->
							<div class="grid grid-cols-2 gap-4">
								<div class="mb-5 mt-2">
									<label for="name" class="inputLabelMedium">Package/Tier Name
										<span class="text-red-500">*</span>
									</label>
									<input type="text" name="name" class="mediumInput" placeholder="max 255-characters">
								</div>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="duration" class="inputLabelMedium">Subscription duration
										<span class="text-red-500">*</span>
										<span class="text-sm text-gray-400 font-light"> (Days in Integer. Like monthly is 30,
											6-month plan = 180)</span>
									</label>
									<input type="text" name="duration" class="mediumInput" placeholder="30, 180, 365">
								</div>
							</div>
							<!-- -->
							<div class="grid grid-cols-2 gap-4">
								<div class="mb-5 mt-2">
									<label for="starting_price" class="inputLabelMedium">Starting Price
										<span class="text-red-500">*</span>
										<span class="text-sm text-gray-400 font-light"> (First or before-discount price)</span>
									</label>
									<input type="number" name="starting_price" class="mediumInput" placeholder="numbers only"
										step="0.01">
								</div>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="final_price" class="inputLabelMedium">Final Price
										<span class="text-red-500">*</span>
										<span class="text-sm text-gray-400 font-light"> (Payable/Selling price)</span>
									</label>
									<input type="number" name="final_price" class="mediumInput" placeholder="numbers only"
										step="0.01">
								</div>
							</div>
							<!-- -->
							<div class="grid grid-cols-2 gap-4">
								<div class="hidden mb-5 mt-2">
									<label for="price_label" class="inputLabelMedium">Price Label
										<span class="text-sm text-gray-400 font-light"> (Like, Best Deal! Great Offer!)</span>
									</label>
									<input type="text" name="price_label" class="mediumInput" placeholder="max 255-characters">
								</div>
								<div class="hidden mb-5 mt-2">
									<label for="slogan" class="inputLabelMedium">Slogan</label>
									<input type="text" name="slogan" class="mediumInput" placeholder="max 255-characters">
								</div>
							</div>

							<!-- -->
							<div class="grid grid-cols-2 gap-4">
								<div class="mb-5 mt-2">
									<label for="benefits" class="inputLabelMedium">Benefits
										<span class="text-sm text-gray-400 font-light"> (Max 2000 characters. Use
											Ctrl+Shift+V for safe pasting)</span>
									</label>
									<textarea class="summernote" name="benefits" id="benefits"></textarea>
								</div>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="description" class="inputLabelMedium">Description
										<span class="text-sm text-gray-400 font-light"> (Max 2000 characters. Use
											Ctrl+Shift+V for safe pasting)</span>
									</label>
									<textarea class="summernote" name="description" id="description"></textarea>
								</div>
							</div>
							<!--  -->
							<div class="grid grid-cols-2 gap-x-4 p-4 shadow-lg rounded-lg">
								<h3 class="col-span-2">Mobile Subscription <span class="text-base text-red-500">* (This section
										is only required for mobile subscriptions)</span></h3>
								<span class="col-span-2 text-sm text-gray-400 font-light">(This is for placing correct tier id
									into subscription records & mostly for display purpose without opening RevenueCat each time.
									However, it doesn't change how app stores manage their subscriptions.)</span>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="google_product_id" class="inputLabelMedium">Google Product Identifier
										<span class="text-red-500">*</span>
									</label>
									<input type="text" name="google_product_id" class="mediumInput">
								</div>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="apple_product_id" class="inputLabelMedium">Apple Product Identifier
										<span class="text-red-500">*</span>
									</label>
									<input type="text" name="apple_product_id" class="mediumInput">
								</div>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="google_entitlement" class="inputLabelMedium">Google Entitlement
									</label>
									<input type="text" name="google_entitlement" class="mediumInput">
								</div>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="apple_entitlement" class="inputLabelMedium">Apple Entitlement
									</label>
									<input type="text" name="apple_entitlement" class="mediumInput">
								</div>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="google_rest_api_id" class="inputLabelMedium">Google REST API ID
									</label>
									<input type="text" name="google_rest_api_id" class="mediumInput">
								</div>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="apple_rest_api_id" class="inputLabelMedium">Apple REST API ID
									</label>
									<input type="text" name="apple_rest_api_id" class="mediumInput">
								</div>
							</div>
							<!-- -->
							<div class="hidden grid-cols-2 gap-4 p-4 shadow-lg rounded-lg">
								<h3 class="col-span-2">Web Subscription <span class="text-base text-red-500">* (This section is
										only required for web based Stripe subscriptions)</span></h3>
								<span class="col-span-2 text-sm text-gray-400 font-light">(This is for Stripe payments.
									❗However, Wrong ID can lead to wrong payment process.)</span>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="stripe_product_id" class="inputLabelMedium">Stripe Product ID
										<span class="text-red-500">*</span>
									</label>
									<input type="text" name="stripe_product_id" class="mediumInput" placeholder="integers only">
								</div>
								<!--  -->
								<div class="mb-5 mt-2">
									<label for="stripe_price_id" class="inputLabelMedium">Stripe Price ID
										<span class="text-red-500">*</span>
									</label>
									<input type="text" name="stripe_price_id" class="mediumInput">
								</div>
							</div>
							<!-- -->

							<div class="my-5">
								<label for="image" class="inputLabelMedium">Tier Image
									<span class="text-sm text-gray-400 font-light"> (Max 10MB)</span>
								</label>

								<input type="file" id="imageDropify" name="image" class="dropify" data-height="150" />

								<input type="hidden" name="remove_image" id="remove_image" value="0">
							</div>
							<!-- -->
							<div class="mb-5 mt-2">
								<label for="status" class="inputLabelMedium">Active Status</label>
								<select name="status" class="dropdownSelectInputs">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select>
							</div>

						</div>

						<button type="submit" class="saveButton my-4">Create Tier</button>
					</form>
				</div>
			</div>
			<!-- --------------------------------- -->
		</div>
		<!--------->
		<div id="hs-destroy-heading-two"
			class=" hs-accordion-to-destroy hs-accordion hs-accordion-active:border-gray-200 PrelineAccordionItemParent">

			<x-ui_items.accordion.accordion_toggle_button toggleLabel="All Tiers::Web" />

			<!-- --------------------------------- -->
			<div id="hs-destroy-collapse-two"
				class="hidden hs-accordion-content {{ $ACCORDION_2_VISIBILITY }} w-full overflow-hidden transition-[height] duration-300"
				role="region" aria-labelledby="hs-destroy-heading-two">
				<div class="p-3 grid grid-cols-3 gap-8">

					@foreach ($webTiers as $index => $tier)
						<div class="p-4 rounded-2xl border hover:bg-emerald-50 hover:shadow-lg cursor-pointer">
							<div class="flex_x_between_y_center">
								<h3 class="text-gray-400">[{{ $tier?->id }}] {{ $tier?->name ?? "N/A" }}</h3>
								<b class="capitalize">{{ $tier?->platform ?? "N/A" }}</b>
							</div>
							<div class="flex justify-between">
								<h5 class="appTextBlue">Slogan: {{ $tier?->slogan ?? 'N/A' }}</h5>
								{{-- --}}
								<div>
									<a href="{{ route('backend_subscription_tier_show', $tier->id) }}" class="showButton p-1">
										{!! \App\Helpers\IconPack::edit(['class' => 'iconPackItem w-3 h-3 text-gray-100']) !!}
									</a>
									<form action="{{ route('backend_subscription_tier_delete', $tier->id) }}" method="POST"
										class="inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="deleteButton p-1"
											onclick="return confirm('Are you sure you want to delete this resource?');">
											{!! \App\Helpers\IconPack::trash(['class' => 'iconPackItem w-3 h-3 text-gray-100']) !!}
										</button>
									</form>
								</div>
							</div>
							<div class="flex items-center gap-8">
								<h3>{{ $tier?->final_price ?? 'N/A' }}$</h3>
								<h4 class="line-through">{{ $tier?->starting_price ?? 'N/A' }}$</h4>
								<p class="text-gray-500">{{ $tier?->duration ?? 'N/A' }} day(s)
								</p>
							</div>
							<h6>Label: {{ $tier?->price_label ?? 'N/A' }}</h6>
							<ul class="text-sm">{!! $tier?->benefits ?? 'N/A' !!}</ul>
						</div>
					@endforeach

				</div>
			</div>
			<!-- --------------------------------- -->

		</div>
		<!--------->
		<div id="hs-destroy-heading-three"
			class="hs-accordion-to-destroy hs-accordion hs-accordion-active:border-gray-200 PrelineAccordionItemParent">

			<x-ui_items.accordion.accordion_toggle_button toggleLabel="All Tiers::Mobile" />

			<!-- --------------------------------- -->
			<div id="hs-destroy-collapse-three"
				class="hs-accordion-content {{ $ACCORDION_3_VISIBILITY }} w-full overflow-hidden transition-[height] duration-300"
				role="region" aria-labelledby="hs-destroy-heading-three">
				<div
					class="p-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 bg-gray-50 dark:bg-neutral-900 rounded-2xl">
					@foreach ($mobileTiers as $index => $tier)
						<div
							class="relative flex flex-col justify-between overflow-hidden rounded-2xl border border-gray-100 dark:border-neutral-800 bg-white dark:bg-neutral-800 p-6 shadow-sm hover:shadow-xl hover:border-blue-500/30 dark:hover:border-blue-500/40 transition-all duration-300 group">

							{{-- Optional Top Accent Line for Active Tiers --}}
							@if($tier?->status === 1)
								<div class="absolute top-0 inset-x-0 h-0.75 bg-linear-to-r range from-blue-500 to-indigo-500">
								</div>
							@endif

							<div>
								{{-- Header Action Row --}}
								<div class="flex justify-between items-start gap-4 mb-4">
									<div class="flex flex-col gap-1.5">
										{{-- Platform Badge --}}
										<div class="flex items-center gap-2">
											<span
												class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium tracking-wide uppercase shadow-sm
													{{ $tier?->platform === 'apple' ? 'bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-100' : '' }}
													{{ $tier?->platform === 'google' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400' : '' }}
													{{ $tier?->platform === 'stripe' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400' : '' }}
													{{ !in_array($tier?->platform, ['apple', 'google', 'stripe']) ? 'bg-gray-100 text-gray-700 dark:bg-neutral-700 dark:text-neutral-300' : '' }}">
												{{ $tier?->platform ?? 'N/A' }}
											</span>

											{{-- Status Indicator --}}
											<span
												class="w-2 h-2 rounded-full {{ $tier?->status === 1 ? 'bg-emerald-500 animate-pulse' : 'bg-gray-300 dark:bg-neutral-600' }}"
												title="{{ $tier?->status === 1 ? 'Active' : 'Inactive' }}"></span>
										</div>

										<h3
											class="text-base font-bold text-gray-800 dark:text-neutral-100 tracking-tight line-clamp-1">
											{{ $tier?->name ?? 'Untitled Tier' }}
										</h3>
										<span class="text-[10px] font-mono text-gray-500 dark:text-neutral-500">UID:
											{{ $tier?->tier_uid ?? $tier?->id }}</span>
									</div>

									{{-- Actions Menu --}}
									<div
										class="flex items-center gap-1 bg-gray-50 dark:bg-neutral-700/50 p-1 rounded-xl opacity-80 group-hover:opacity-100 transition-opacity">
										<a href="{{ route('backend_subscription_tier_show', $tier->id) }}"
											class="p-1.5 rounded-lg text-gray-500 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-neutral-700 transition"
											title="Edit Tier">
											{!! \App\Helpers\IconPack::edit(['class' => 'w-4 h-4']) !!}
										</a>

										<form action="{{ route('backend_subscription_tier_delete', $tier->id) }}" method="POST"
											class="inline-block">
											@csrf
											@method('DELETE')
											<button type="submit"
												class="p-1.5 rounded-lg text-gray-500 dark:text-neutral-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-white dark:hover:bg-neutral-700 transition"
												onclick="return confirm('Are you completely sure you want to delete this tier?');"
												title="Delete Tier">
												{!! \App\Helpers\IconPack::trash(['class' => 'w-4 h-4']) !!}
											</button>
										</form>
									</div>
								</div>

								{{-- Tier Image & Slogan --}}
								<div class="flex gap-3 items-start mb-5">
									@if($tier?->image_url)
										<img src="{{ $tier->image_url }}" alt="{{ $tier->name }}"
											class="w-12 h-12 rounded-xl object-cover border border-gray-100 dark:border-neutral-700 shadow-sm shrink-0">
									@else
										<div
											class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-neutral-700 flex items-center justify-center text-gray-500 dark:text-neutral-500 shrink-0 font-bold text-sm">
											{{ strtoupper(substr($tier?->name ?? 'T', 0, 2)) }}
										</div>
									@endif
									<p class="text-xs text-gray-500 dark:text-neutral-400 leading-relaxed line-clamp-2 pt-0.5">
										{{ $tier?->slogan ?? 'No promotional slogan provided for this tier.' }}
									</p>
								</div>

								{{-- Pricing Block --}}
								<div
									class="bg-gray-50 dark:bg-neutral-900/50 rounded-xl p-3.5 mb-5 flex items-center justify-between border border-gray-100/50 dark:border-neutral-800/50">
									<div>
										<div class="flex items-baseline gap-2">
											<span class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
												${{ number_format($tier?->final_price, 2) }}
											</span>
											@if($tier?->starting_price && $tier->starting_price > $tier->final_price)
												<span class="text-xs text-gray-500 dark:text-neutral-500 line-through">
													${{ number_format($tier->starting_price, 2) }}
												</span>
											@endif
										</div>
										@if($tier?->price_label)
											<span
												class="text-[10px] font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
												{{ $tier->price_label }}
											</span>
										@endif
									</div>

									<div class="text-right">
										<span class="block text-xs font-bold text-gray-700 dark:text-neutral-300">
											{{ $tier?->duration ?? '0' }} Days
										</span>
										<span class="text-[10px] text-gray-500 dark:text-neutral-500 uppercase tracking-tight block">
											Validity Period
										</span>
									</div>
								</div>

								{{-- Benefits Title --}}
								<div class="mb-2 flex items-center gap-2">
									<span
										class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-neutral-500">Included
										Features</span>
									<div class="h-px bg-gray-200 dark:bg-neutral-700 grow"></div>
								</div>

								{{-- Benefits HTML List --}}
								<div class="text-xs text-gray-600 dark:text-neutral-300 space-y-1.5 prose prose-sm dark:prose-invert max-w-none 
												prose-ul:list-disc prose-ul:pl-4 prose-li:my-0.5">
									@if($tier?->benefits && $tier->benefits !== 'N/A')
										{!! $tier->benefits !!}
									@else
										<span class="text-gray-500 dark:text-neutral-600 italic">No features defined.</span>
									@endif
								</div>
							</div>

							{{-- Platform Sync IDs Footer --}}
							<div
								class="mt-6 pt-4 border-t border-dashed border-gray-100 dark:border-neutral-700 flex flex-wrap gap-1.5 opacity-60 hover:opacity-100 transition-opacity">
								@if($tier?->rc_entitlement_id)
									<span
										class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-gray-100 dark:bg-neutral-700 text-gray-500 dark:text-neutral-400"
										title="RevenueCat ID">RC: {{ $tier->rc_entitlement_id }}</span>
								@endif
								@if($tier?->stripe_price_id)
									<span
										class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400"
										title="Stripe Price ID">Stripe Active</span>
								@endif
								@if($tier?->apple_product_id)
									<span
										class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300"
										title="Apple Product ID">AppStore</span>
								@endif
								@if($tier?->google_subscription_id)
									<span
										class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400"
										title="Google Play ID">PlayStore</span>
								@endif
							</div>

						</div>
					@endforeach
				</div>
			</div>
			<!-- --------------------------------- -->

		</div>
		<!-- -------------------------------- APP AREA Starts -------------------------------- -->


</x-app-layout>