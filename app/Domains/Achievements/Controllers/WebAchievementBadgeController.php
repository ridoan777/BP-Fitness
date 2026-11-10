<?php

namespace App\Domains\Achievements\Controllers;

use App\Domains\Achievements\Models\AchievementBadge;
use App\Helpers\FileHelpers\FileManagement;
use App\Helpers\Ui\{ConvertDynamicTimezone, DatatableHelper};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class WebAchievementBadgeController extends Controller
{
	public function index()
	{
		return view('Admin.sidebar.Achievements.badges.index');
	}

	public function create()
	{
		return view('Admin.sidebar.Achievements.badges.create');
	}

	public function show(AchievementBadge $singleBadge, string $slug)
	{
		return view('Admin.sidebar.Achievements.badges.show', compact('singleBadge'));
	}

	public function store(Request $request)
	{
		try {
			$validated = $request->validate([
				'id' => ['nullable', 'integer', 'exists:achievement_badges,id'],
				'name' => ['required', 'string', 'max:255'],
				'points_required' => ['required', 'integer', 'min:0'],
				'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
				'remove_image' => ['nullable', 'boolean'],
				'status' => ['required', 'integer', 'in:0,1'],
			]);

			$existingBadge = null;

			// ------------ CHECKING EXISTANCE + UID ------------
			if (isset($validated['id']) && $validated['id']) {
				$existingBadge = AchievementBadge::findOrFail((int)$validated['id']);
			}
			// ------------ CHECKING EXISTANCE + UID ------------

			// ------------ FILE HANDLING ------------
			$DISK_FOLDER = config('filesystems.default');
			$FILE_FOLDER = 'achievement_badges';

			if ($request->hasFile('image') || $request->boolean('remove_image')) {
				$storedFile = FileManagement::handleFile(
					$request->file('image'),
					'BADGE-' . ($validated['name'] ?? 'no-name'),
					$existingBadge?->image ?? null,
					$FILE_FOLDER,
					$DISK_FOLDER,
					$request->boolean('remove_image')
				);
			} else {
				$storedFile = ['path' => $existingBadge?->image ?? null];
			}
			// ------------ FILE HANDLING ------------

			$badge = AchievementBadge::updateOrCreate(
				[
					'id' => $existingBadge?->id,
				],
				[
					'name' => $validated['name'],
					'points_required' => (int)$validated['points_required'],
					'image' => $storedFile['path'] ?? null,
					'status' => (int)$validated['status'],
				]
			);

			return redirect()->back()->with('success', "A badge '{$badge->name}' is saved successfully.");
		} catch (Throwable $e) {
			return back()->with('error', 'Action error: ' . $e->getMessage())->withInput();
		}
	}

	public function toggle(Request $request, AchievementBadge $singleBadge)
	{
		try {
			$singleBadge->update([
				'status' => !$singleBadge->status,
			]);

			return response()->json(['success' => true]);
		} catch (Exception $e) {
			return response()->json(['success' => false, 'error' => $e->getMessage()]);
		}
	}

	public function delete(Request $request, AchievementBadge $singleBadge)
	{
		try {
			$name = $singleBadge?->name;
			$DISK_FOLDER = config('filesystems.default');

			if ($singleBadge?->image) {
				FileManagement::deleteFile($singleBadge->image, $DISK_FOLDER);
			}

			$singleBadge->delete();

			return redirect()->route('backend_achievement_badge_index')->with('success', "A badge '{$name}' has been deleted successfully.");
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Action Error: ' . $e->getMessage())->withInput();
		}
	}

	public function bulkDelete(Request $request)
	{
		$request->validate([
			'ids' => 'required|array',
			'ids.*' => 'integer|exists:achievement_badges,id',
		]);

		$ids = $request->ids;
		$DISK_FOLDER = config('filesystems.default');
		$badges = AchievementBadge::whereIn('id', $ids)->get();

		if ($badges->isEmpty()) {
			return back()->with('error', 'No valid badges selected.');
		}

		try {
			foreach ($badges as $badge) {
				if ($badge?->image) {
					FileManagement::deleteFile($badge->image, $DISK_FOLDER);
					Storage::delete($badge->image);
				}
				/** @var AchievementBadge $badge */
				$badge->delete();
			}

			return redirect()->back()->with('success', 'Selected badges are deleted.');
		} catch (Throwable $e) {
			return back()->with('error', 'Bulk delete failed. No changes were made.');
		}
	}

	public function datatable(Request $request)
	{
		try {
			$columns = ['id', 'id', 'id', 'id', 'name', 'points_required', 'status', 'created_at', 'id'];

			$draw = (int)$request->input('draw');
			$start = (int)$request->input('start', 0);
			$length = (int)$request->input('length', 25);
			$orderColIndex = (int)$request->input('order.0.column', 7);
			$orderCol = $columns[$orderColIndex] ?? 'created_at';
			$orderDir = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';

			$query = AchievementBadge::select(['id', 'name', 'points_required', 'image', 'status', 'created_at']);

			if ($search = $request->input('search.value')) {
				$query->where(function ($q) use ($search) {
					$q->where('name', 'like', "%{$search}%")
					->orWhere('points_required', 'like', "%{$search}%");
				});
			}

			$recordsTotal = AchievementBadge::count();
			$recordsFiltered = $query->count();

			$data = $query->orderBy($orderCol, $orderDir)->offset($start)->limit($length)->get();

			$sn = $start;
			$zones = ConvertDynamicTimezone::handle();

			$data->transform(function ($row) use (&$sn, $zones) {
				$row->DT_RowId = 'row_' . $row->id;
				$row->view_url = route('backend_achievement_badge_show', ['singleBadge' => $row->id, 'slug' => Str::slug($row->name)]);

				$row->checkbox = '<input type="checkbox" name="ids[]" value="' . $row->id . '" class="row-check w-4 h-4 rounded border-gray-400 cursor-pointer checked:bg-blue-600 checked:border-blue-600">';

				$row->SN = ++$sn;

				$row->name = '<span class="text-sm font-medium dark:text-gray-200">' . e($row->name) . '</span>';

				$row->points_required_label = '<span class="text-xs dark:text-gray-200">' . e(number_format((int) $row->getRawOriginal('points_required'))) . '</span>';

				$row->image_preview = '<img src="' . ($row->image_url ?: asset('site_assets/dummies/dummy_no_file.jpg')) . '" alt="' . e($row->name) . '" class="h-12 w-12 rounded-lg object-cover mx-auto border border-gray-200 dark:border-gray-700">';

				$row->status_label = DatatableHelper::phaseColor($row->status);

				$row->created_at_formatted = $row->created_at ? Carbon::parse($row->created_at, 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A';

				$row->actions = DatatableHelper::datatableAction($row, false, true, 'backend_achievement_badge_toggle', 'backend_achievement_badge_delete');

				$row->DT_RowAttr = DatatableHelper::rowClickBehaviour($row->view_url, 'modal');

				return $row;
			});

			return DatatableHelper::handleDatatableSuccess($draw, $recordsTotal, $recordsFiltered, $data);
		} catch (Exception $e) {
			return DatatableHelper::handleDatatableError($request, $e, true, 'Achievement Badge Datatable Error');
		}
	}
}
