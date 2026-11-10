<?php

namespace App\Domains\Achievements\Controllers;

use App\Domains\Achievements\Models\{AchievementBadge, AchievementRecord, AchievementHead};
use App\Helpers\Ui\{ConvertDynamicTimezone, DatatableHelper};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Throwable;

class WebAchievementRecordController extends Controller
{
	public function index()
	{
		return view('Admin.sidebar.Achievements.records.index');
	}

	public function create()
	{
		return redirect()->route('backend_achievement_earner_index')->with('info', 'Achievement earner records are system generated.');
	}

	public function show(AchievementRecord $singleEarner, string $slug)
	{
		$userAchievements = AchievementRecord::with([
			'earnerRelatonWith_User:id,name,username,email,phone,user_uid,avatar,user_role,status,created_at',
			'earnerRelatonWith_AchievementHead:id,title,type,required_value,points,status',
		])->userId((int)$singleEarner->user_id)->latest('unlocked_at')->get();

		return view('Admin.sidebar.Achievements.records.show', compact('userAchievements'));
	}

	public function toggle(Request $request, AchievementRecord $singleEarner)
	{
		try {
			$newStatus = $singleEarner->status ? 0 : 1;

			AchievementRecord::where('user_id', $singleEarner->user_id)->update([
				'status' => $newStatus,
			]);

			return response()->json(['success' => true]);
		} catch (Exception $e) {
			return response()->json(['success' => false, 'error' => $e->getMessage()]);
		}
	}

	public function delete(Request $request, AchievementRecord $singleEarner)
	{
		try {
			$userName = $singleEarner->earnerRelatonWith_User?->name ?? ('User #' . $singleEarner->user_id);

			AchievementRecord::where('user_id', $singleEarner->user_id)->delete();

			return redirect()->route('backend_achievement_earner_index')->with('success', "Achievement records for '{$userName}' have been deleted successfully.");
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Action Error: ' . $e->getMessage())->withInput();
		}
	}

	public function deleteRecord(Request $request, AchievementRecord $singleRecord)
	{
		try {
			$achievementTitle = $singleRecord->earnerRelatonWith_AchievementHead?->title ?? ('Record #' . $singleRecord->id);

			$singleRecord->delete();

			return redirect()->back()->with('success', "Achievement record '{$achievementTitle}' has been deleted successfully.");
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Action Error: ' . $e->getMessage())->withInput();
		}
	}

	public function bulkDelete(Request $request)
	{
		$request->validate([
			'ids' => 'required|array',
			'ids.*' => 'integer|exists:achievement_earner_users,user_id',
		]);

		try {
			AchievementRecord::whereIn('user_id', $request->ids)->delete();

			return redirect()->back()->with('success', 'Selected user achievement records are deleted.');
		} catch (Throwable $e) {
			return back()->with('error', 'Bulk delete failed. No changes were made.');
		}
	}

	public function bulkDeleteRecords(Request $request)
	{
		$ids = $request->input('ids', []);

		if (is_string($ids)) {
			$ids = array_values(array_filter(array_map('trim', explode(',', $ids))));
			$request->merge(['ids' => $ids]);
		}

		$request->validate([
			'ids' => 'required|array',
			'ids.*' => 'integer|exists:achievement_earner_users,id',
		]);

		try {
			AchievementRecord::whereIn('id', $request->ids)->delete();

			return redirect()->back()->with('success', 'Selected achievement records are deleted.');
		} catch (Throwable $e) {
			return back()->with('error', 'Bulk delete failed. No changes were made.');
		}
	}

	public function datatable(Request $request)
	{
		try {
			$columns = ['user_id', 'sn', 'user_name', 'total_challenges_count', 'total_points_count', 'current_badge_name', 'latest_status', 'latest_created_at', 'actions'];

			$draw = (int)$request->input('draw');
			$start = (int)$request->input('start', 0);
			$length = (int)$request->input('length', 25);
			$orderColIndex = (int)$request->input('order.0.column', 7);
			$orderCol = $columns[$orderColIndex] ?? 'latest_created_at';
			$orderDir = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';

			$badges = AchievementBadge::select(['id', 'name', 'image', 'points_required'])->status(1)->orderBy('points_required')->get();

			$allEarners = AchievementRecord::with(['earnerRelatonWith_User:id,name,username,email,phone,user_uid,avatar,user_role,status,created_at'])->select(['id', 'user_id', 'achievement_id', 'points_earned', 'status', 'created_at'])->latest('created_at')->get();

			$groupedUsers = $allEarners->groupBy('user_id');
			$recordsTotal = $groupedUsers->count();

			$rows = collect();

			foreach ($groupedUsers as $userId => $earners) {
				$row = $earners->first();
				$user = $row->earnerRelatonWith_User;
				$totalPointsCount = (int)$earners->sum('points_earned');
				$currentBadge = $badges->filter(function ($badge) use ($totalPointsCount) {
					return (int)$badge->points_required <= $totalPointsCount;
				})->last();

				$row->total_challenges_count = $earners->count();
				$row->total_points_count = $totalPointsCount;
				$row->latest_status = (int)$earners->max('status');
				$row->latest_created_at = $earners->max('created_at');
				$row->user_name = $user?->name ?? 'N/A';
				$row->user_email = $user?->email ?? 'N/A';
				$row->user_uid = $user?->user_uid ?? 'N/A';
				$row->current_badge = $currentBadge ?? (object)[
					'id' => null,
					'name' => 'N/A',
					'image' => null,
				];
				$row->current_badge_name = $currentBadge?->name ?? 'N/A';

				$rows->push($row);
			}

			if ($search = trim((string)$request->input('search.value'))) {
				$searchLower = Str::lower($search);

				$rows = $rows->filter(function ($row) use ($search, $searchLower) {
					$userMatch = str_contains(Str::lower((string)$row->user_name), $searchLower)
						|| str_contains(Str::lower((string)$row->user_email), $searchLower)
						|| str_contains(Str::lower((string)$row->user_uid), $searchLower)
						|| str_contains((string)$row->user_id, $search);

					$achievementMatch = str_contains(Str::lower((string)$row->current_badge_name), $searchLower)
						|| str_contains((string)$row->total_challenges_count, $search);

					if (is_numeric($search)) {
						$numericSearch = (int)$search;
						$pointsMatch = (int)$row->total_points_count <= $numericSearch;
					} else {
						$pointsMatch = str_contains((string)$row->total_points_count, $search);
					}

					return $userMatch || $achievementMatch || $pointsMatch;
				})->values();
			}

			$recordsFiltered = $rows->count();

			$rows = $orderDir === 'asc' ? $rows->sortBy($orderCol) : $rows->sortByDesc($orderCol);

			$rows = $rows->slice($start, $length)->values();

			$sn = $start;
			$zones = ConvertDynamicTimezone::handle();

			$rows->transform(function ($row) use (&$sn, $zones) {
				$user = $row->earnerRelatonWith_User;

				$row->DT_RowId = 'row_' . $row->user_id;
				$row->SN = ++$sn;
				$row->view_url = route('backend_achievement_earner_show', ['singleEarner' => $row->id, 'slug' => Str::slug($user?->name ?? 'user')]);

				$row->checkbox = '<input type="checkbox" name="ids[]" value="' . $row->user_id . '" class="row-check w-4 h-4 rounded border-gray-400 cursor-pointer checked:bg-blue-600 checked:border-blue-600">';

				$row->user = $user ? DatatableHelper::userBlock($user) : 'N/A';

				$row->total_challenges = '<span class="text-xs dark:text-gray-200">' . e(number_format((int)$row->total_challenges_count)) . '</span>';

				$row->total_points = '<span class="text-xs dark:text-gray-200">' . e(number_format((int)$row->total_points_count)) . '</span>';

				$row->current_badge = DatatableHelper::detailBlock($row->current_badge, false, null, true, $row->current_badge?->image);

				$row->status_label = $row->latest_status ? '<span class="text-green-600 font-medium">Active</span>' : '<span class="text-red-600 font-medium">Inactive</span>';

				$row->created_at_formatted = $row->latest_created_at ? Carbon::parse($row->latest_created_at, 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A';

				$row->actions = DatatableHelper::datatableAction($row, false, true, 'backend_achievement_earner_toggle', 'backend_achievement_earner_delete');
				$row->DT_RowAttr = DatatableHelper::rowClickBehaviour($row->view_url, 'modal');

				return $row;
			});

			return DatatableHelper::handleDatatableSuccess($draw, $recordsTotal, $recordsFiltered, $rows);
		} catch (Throwable $e) {
			return DatatableHelper::handleDatatableError($request, $e, true, 'Achievement Earner Datatable Error');
		}
	}
}
