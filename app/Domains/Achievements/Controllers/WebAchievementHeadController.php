<?php

namespace App\Domains\Achievements\Controllers;

use App\Domains\Achievements\Models\AchievementHead;
use App\Helpers\ApiJsonReturnHelper;
use App\Helpers\Errors\ExceptionHandling;
use App\Helpers\Ui\{ConvertDynamicTimezone, DatatableHelper};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class WebAchievementHeadController extends Controller
{
	public function index()
	{
		return view('Admin.sidebar.Achievements.challenge_heads.index');
	}

	public function create()
	{
		return view('Admin.sidebar.Achievements.challenge_heads.create');
	}

	public function show(AchievementHead $singleHead, string $slug)
	{
		return view('Admin.sidebar.Achievements.challenge_heads.show', compact('singleHead'));
	}

	public function store(Request $request)
	{
		try {
			$validated = $request->validate([
				'id' => ['nullable', 'integer', 'exists:achievement_heads,id'],
				'title' => ['required', 'string', 'max:255'],
				'type' => ['required', 'string', 'max:255'],
				'required_value' => ['required', 'integer', 'min:0'],
				'points' => ['nullable', 'integer'],
				'status' => ['required', 'integer', 'in:0,1'],
			]);

			$existingHead = null;

			if (!empty($validated['id'])) {
				$existingHead = AchievementHead::findOrFail((int)$validated['id']);
			}

			$head = AchievementHead::updateOrCreate(
				[
					'id' => $existingHead?->id,
				],
				[
					'title' => $validated['title'],
					'type' => $validated['type'],
					'required_value' => (int)$validated['required_value'],
					'points' => isset($validated['points']) && $validated['points'] !== '' ? (int)$validated['points'] : null,
					'status' => (int)$validated['status'],
				]
			);

			return redirect()->back()->with('success', "An achievement head '{$head->title}' is saved successfully.");
		} catch (Throwable $e) {
			return back()->with('error', 'Action error: ' . $e->getMessage())->withInput();
		}
	}

	public function toggle(Request $request, AchievementHead $singleHead)
	{
		try {
			$singleHead->update([
				'status' => !$singleHead->status,
			]);

			return response()->json(['success' => true]);
		} catch (Exception $e) {
			return response()->json(['success' => false, 'error' => $e->getMessage()]);
		}
	}

	public function delete(Request $request, AchievementHead $singleHead)
	{
		try {
			$title = $singleHead?->title;

			$singleHead->delete();

			return redirect()->route('backend_achievement_head_index')->with('success', "An achievement head '{$title}' has been deleted successfully.");
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Action Error: ' . $e->getMessage())->withInput();
		}
	}

	public function bulkDelete(Request $request)
	{
		$request->validate([
			'ids' => 'required|array',
			'ids.*' => 'integer|exists:achievement_heads,id',
		]);

		$heads = AchievementHead::whereIn('id', $request->ids)->get();

		if ($heads->isEmpty()) {
			return back()->with('error', 'No valid achievement heads selected.');
		}

		try {
			foreach ($heads as $head) {
				/** @var AchievementHead $head */
				$head->delete();
			}

			return redirect()->back()->with('success', 'Selected achievement heads are deleted.');
		} catch (Throwable $e) {
			return back()->with('error', 'Bulk delete failed. No changes were made.');
		}
	}

	public function datatable(Request $request)
	{
		try {
			$columns = ['id', 'id', 'id', 'title', 'type', 'required_value', 'points', 'status', 'created_at', 'id'];

			$draw = (int)$request->input('draw');
			$start = (int)$request->input('start', 0);
			$length = (int)$request->input('length', 25);
			$orderColIndex = (int)$request->input('order.0.column', 8);
			$orderCol = $columns[$orderColIndex] ?? 'created_at';
			$orderDir = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';

			$query = AchievementHead::select(['id', 'title', 'type', 'required_value', 'points', 'status', 'created_at']);

			if ($search = $request->input('search.value')) {
				$query->where(function ($q) use ($search) {
					$q->where('title', 'like', "%{$search}%")
						->orWhere('type', 'like', "%{$search}%")
						->orWhere('required_value', 'like', "%{$search}%")
						->orWhere('points', 'like', "%{$search}%")
						->orWhereRaw('CAST(id AS CHAR) LIKE ?', ["%{$search}%"]);
				});
			}

			$recordsTotal = AchievementHead::count();
			$recordsFiltered = (clone $query)->count();

			$data = $query->orderBy($orderCol, $orderDir)->offset($start)->limit($length)->get();

			$sn = $start;
			$zones = ConvertDynamicTimezone::handle();

			$data->transform(function ($row) use (&$sn, $zones) {
				$row->DT_RowId = 'row_' . $row->id;
				$row->SN = ++$sn;
				$row->view_url = route('backend_achievement_head_show', ['singleHead' => $row->id, 'slug' => Str::slug($row->title)]);

				$row->checkbox = '<input type="checkbox" name="ids[]" value="' . $row->id . '" class="row-check w-4 h-4 rounded border-gray-400 cursor-pointer checked:bg-blue-600 checked:border-blue-600">';

				$row->title = '<span class="text-sm font-medium dark:text-gray-200">' . e($row->title ?? 'N/A') . '</span>';

				$row->type_display = '<span class="text-xs dark:text-gray-200">' . e($row->type?->label() ?? 'N/A') . '</span>';
				
				$row->value = '<span class="text-xs dark:text-gray-200">' . e($row->required_value !== null ? number_format((int)$row->required_value) : 'N/A') . '</span>';

				$row->reward = '<span class="text-xs dark:text-gray-200">' . e($row->points !== null ? number_format((int)$row->points) : 'N/A') . '</span>';

				$row->status_label = $row->status ? '<span class="text-green-600 font-medium">Active</span>' : '<span class="text-red-600 font-medium">Inactive</span>';

				$row->created_at_formatted = $row->created_at ? Carbon::parse($row->created_at, 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A';

				$row->actions = DatatableHelper::datatableAction($row, false, true, 'backend_achievement_head_toggle', 'backend_achievement_head_delete');

				$row->DT_RowAttr = DatatableHelper::rowClickBehaviour($row->view_url, 'modal');

				$row->name = strip_tags($row->title);
				$row->points_required_label = strip_tags($row->required_value);

				return $row;
			});

			return DatatableHelper::handleDatatableSuccess($draw, $recordsTotal, $recordsFiltered, $data);
		} catch (Exception $e) {
			return DatatableHelper::handleDatatableError($request, $e, true, 'Achievement Head Datatable Error');
		}
	}

	public function pointPoll(Request $request){
		try {
			$pointPoll = (int)AchievementHead::status(1)->sum('points');
	
			return ApiJsonReturnHelper::handle(true, 200, "Total point poll has been fetched sucessfully!", $pointPoll);
	
		} catch (Throwable $e) {
			return ExceptionHandling::handle('fetching point poll', $e);
		}
	}
}
