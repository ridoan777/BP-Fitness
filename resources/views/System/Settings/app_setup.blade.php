<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Settings', 'url' => 'backend_settings_index', 'icon' => 'gearLine'],
			['label' => 'App Setup'],
		];

		$existingOnboarding = collect($onboarding ?? []);
	@endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />
	<x-indicators.page_header_widget pageName="Settings: App Onboarding" />
	<x-ui_items.banners.announcement_banner rolesToShow="admin,super_admin"
		message="Onboarding screens are one of the major sections of user attractions. It must be faster, better, eye-catchy, and screen-fit. Make sure not to use more than 5-6 images. And use smaller-sized (not more than 512 Kb), portrait-oriented, but high-quality images."
		bgColor="bg-yellow-300" textColor="text-gray-800" />
	<x-indicators.alert-component message="Form Submission" />

	<div class="p-3">
		<section
			class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
			<div class="my-5">

				<form method="POST" action="{{ route('backend_settings_app_setup_store') }}"
					class="rounded-lg p-2 dark:bg-neutral-700" enctype="multipart/form-data">
					@csrf

					<div class="my-5 rounded-lg border border-gray-200 p-4 dark:border-neutral-600">
						<div class="flex items-center justify-between gap-3">
							<div>
								<h3 class="inputLabelMedium">Mobile Onboarding Screens</h3>
								<p class="text-sm text-gray-400">
									Add one onboarding block at a time with a title and a single image.
									Save once to send all blocks together.
								</p>
							</div>

							<button type="button" id="addOnboardingBlock"
								class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
								Add Block
							</button>
						</div>

						<x-ui_items.forms.input-error class="mt-2" :messages="$errors->get('gallery_file')" />

						<div id="onboardingBlocks" class="mt-5 grid gap-4">
							<div class="rounded-lg border border-dashed border-gray-300 p-4 dark:border-neutral-600">
								<label class="inputLabelMedium">Onboarding Title</label>
								<input type="text" name="onboarding_titles[]" class="normalInput mt-2"
									placeholder="Example: Track your daily progress">

								<div class="mt-4">
									<label class="inputLabelMedium">Onboarding Image <span
										class="grayLabel">(Max 512 KB each) (Best orientation: Portrait or 9x16) </span><span class="text-sm text-yellow-500 font-light"> (allowed formats: jpeg,png,jpg,webp)</span></label>
									<input type="file" name="gallery_file[]" class="dropify onboardingDropify mt-2"
										data-height="180" accept="image/*">
								</div>
							</div>
						</div>
					</div>

					@if ($existingOnboarding->isNotEmpty())
						<div class="my-5 rounded-lg border border-gray-200 p-4 dark:border-neutral-600">
							<h3 class="inputLabelMedium">Existing Onboarding Screens</h3>
							<div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
								@foreach ($existingOnboarding as $gallery)
									<div class="rounded-lg border border-gray-200 p-3 dark:border-neutral-600">
										<a href="{{ asset($gallery->value) }}" target="_blank" rel="noopener noreferrer">
											<img src="{{ asset($gallery->value) }}"
												class="h-96 w-full rounded object-fill hover:scale-105" alt="{{ $gallery->name }}">
										</a>

										<p class="my-4 p-1 rounded-lg appBorder text-sm font-medium appText text-center">
											{{ $gallery->title }}
										</p>

										<label class="mt-2 flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300">
											<input type="checkbox" name="remove_onboarding_gallery_ids[]" value="{{ $gallery->id }}">
											[{{ $gallery->id }}]-Remove
										</label>
									</div>
								@endforeach
							</div>
						</div>
					@endif

					<button type="submit" class="saveButton my-4">Save</button>
				</form>

			</div>
		</section>
	</div>

	<script>
		$(document).ready(function () {
			let onboardingBlockIndex = 1;

			function initDropify(target) {
				$(target).dropify();
			}

			initDropify('.onboardingDropify');

			$('#addOnboardingBlock').on('click', function () {
				const blockHtml = `
					<div class="rounded-lg border border-dashed border-gray-300 p-4 dark:border-neutral-600 onboarding-block">
						<div class="flex items-center justify-between gap-3">
							<label class="inputLabelMedium">Onboarding Title</label>
							<button type="button"
								class="removeOnboardingBlock rounded-md bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-500">
								Remove
							</button>
						</div>

						<input type="text" name="onboarding_titles[]" class="normalInput mt-2"
							placeholder="Example: Screen ${onboardingBlockIndex + 1}">

						<div class="mt-4">
							<label class="inputLabelMedium">Onboarding Image <span
										class="grayLabel">(Max 512 KB each) (Best orientation: Portrait or 9x16) </span><span class="text-sm text-yellow-500 font-light"> (allowed formats: jpeg,png,jpg,webp)</span></label>
							<input type="file" name="gallery_file[]" class="dropify onboardingDropify mt-2"
								data-height="180" accept="image/*">
						</div>
					</div>
				`;

				$('#onboardingBlocks').append(blockHtml);
				const $newDropify = $('#onboardingBlocks .onboardingDropify').last();
				initDropify($newDropify);
				onboardingBlockIndex++;
			});

			$(document).on('click', '.removeOnboardingBlock', function () {
				$(this).closest('.onboarding-block').remove();
			});
		});
	</script>

</x-app-layout>