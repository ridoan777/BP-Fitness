<?php

namespace App\Actions\Settings;

use App\Helpers\FileHelpers\FileManagement;
use App\Models\System\Settings\OptionSiteSetup;
use App\Models\Users\User;
use Illuminate\Support\Str;

class WebOnboardingGalleryAction
{
	public function execute(array $validated, ?User $USER = null): array
	{
		$FILE_FOLDER = 'onboarding_gallery';

		$deletedGalleryIds = [];
		$storedGalleryFiles = [];

		// ------------ DELETE ONBOARDING GALLERIES ------------
		$targetGalleryIds = collect($validated['remove_onboarding_gallery_ids'] ?? $validated['remove_product_gallery_ids'] ?? [])->filter()->map(fn($id) => (int)$id)->unique()->values()->toArray();

		if (!empty($targetGalleryIds)) {
			$targetFiles = OptionSiteSetup::where('type', 'onboarding_screen')->whereIn('id', $targetGalleryIds)->get();

			foreach ($targetFiles as $file) {
				if (!empty($file->value)) {
					FileManagement::handleDirectPublicAsset(null, 'onboarding_gallery', $file->value, $FILE_FOLDER, true);
				}

				$deletedGalleryIds[] = $file->id;
				/** @var OptionSiteSetup $file */
				$file->delete();
			}
		}
		// ------------ DELETE ONBOARDING GALLERIES ------------

		// ------------ STORE ONBOARDING GALLERIES ------------
		if (!empty($validated['gallery_file'] ?? [])) {
			foreach ($validated['gallery_file'] as $index => $galleryFile) {
				if (!$galleryFile || !$galleryFile->isValid()) {
					continue;
				}

				$storedFile = FileManagement::handleDirectPublicAsset(
					$galleryFile,
					'onboarding_gallery-' . now()->timestamp . '-' . ($index + 1),
					null,
					$FILE_FOLDER,
					false
				);

				$storedGalleryFiles[] = OptionSiteSetup::create([
					'type' => 'onboarding_screen',
					'name' => 'gallery_' . $index+1,
					'value' => $storedFile,
					'log' => 'Last updated by: ' . ($USER?->email ?? 'System'),
					'status' => 1,
				]);
			}
		}
		// ------------ STORE ONBOARDING GALLERIES ------------

		return [
			'deleted_gallery_ids' => $deletedGalleryIds,
			'gallery_images' => $storedGalleryFiles,
		];
	}
}
