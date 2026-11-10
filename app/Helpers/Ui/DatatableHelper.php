<?php

namespace App\Helpers\Ui;

use App\Helpers\IconPack;
use Illuminate\Support\Facades\{Storage,Log};
use Illuminate\Support\Str;

class DatatableHelper
{
   // -----------------------------------------------
   public static function datatableAction($row, $view = false, $toggle = true, $toggleRoute = null, $deleteRoute = null, $viewRoute = null)
   {
      $row->status_label = $row->status
         ? '<span class="text-green-600 font-medium">Active</span>'
         : '<span class="text-red-600 font-medium">Inactive</span>';

      $toggleIcon = $row->status
         ? IconPack::toggleOn(['class' => 'iconPackItem w-8 h-8 text-green-500'])
         : IconPack::toggleOff(['class' => 'iconPackItem w-8 h-8 text-red-500']);

      $trashIcon = IconPack::trash(['class' => 'iconPackItem w-3 h-3 text-gray-100']);
      $displayIcon = IconPack::envelopeBulk(['class' => 'iconPackItem w-3 h-3 text-gray-100']);

      $viewBtn = '';
      $toggleBtn = '';
      $deleteBtn = '';

      if ($view && $viewRoute) {
         $viewBtn = '
            <a href="' . route($viewRoute, ['singleGroup' => $row->id, 'slug' => $row->slug]) . '" class="bg-blue-400 p-2 rounded inline-flex items-center">
                  ' . $displayIcon . '
            </a>';
      }

      if ($toggle && $toggleRoute) {
         $toggleBtn = '
            <form action="' . route($toggleRoute, $row->id) . '" method="POST" class="toggleForm inline-flex items-center p-2">
                ' . csrf_field() . '
                <button type="submit">' . $toggleIcon . '</button>
            </form>';
      }

      if ($deleteRoute) {
         $deleteBtn = '
            <form action="' . route($deleteRoute, $row->id) . '" method="POST" class="deleteForm inline-flex items-center p-2">
                ' . csrf_field() . method_field('DELETE') . '
                <button type="submit" class="bg-red-500 p-2 rounded">' . $trashIcon . '</button>
            </form>';
      }

      return '
        <div class="flex justify-center items-center space-x-2">
            ' . $viewBtn . '
            ' . $toggleBtn . '
            ' . $deleteBtn . '
        </div>';
   }

   // -----------------------------------------------

   public static function rowClickBehaviour(string $viewUrl, string $mode = 'page'): array
   {
      $attrs = [
         'data-mode' => $mode,
         'data-view-url' => $viewUrl,
         'class' => 'cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700 transition'
      ];

      if ($mode === 'modal') {
         $attrs['data-modal-trigger'] = "datatableViewModalTrigger";
         $attrs['data-modal-content'] = "datatableViewModalContent";
      }

      return $attrs;
   }
   // -----------------------------------------------

   public static function handleDatatableSuccess($draw, $recordsTotal, $recordsFiltered, $data)
   {
      return response()->json([
         'draw' => $draw,
         'recordsTotal' => $recordsTotal,
         'recordsFiltered' => $recordsFiltered,
         'data' => $data,
      ]);
   }
   // -----------------------------------------------

   public static function handleDatatableError($request, $error, $log = true, $logMssg = null)
   {
      if ($log) {
         Log::error($logMssg . ': ' . $error->getMessage());
      }
      return response()->json([
         'draw' => intval($request->input('draw')),
         'recordsTotal' => 0,
         'recordsFiltered' => 0,
         'data' => [],
         'error' => config('app.debug') ? $error->getMessage() : 'An error occurred.'
      ], 500);
   }
   // -----------------------------------------------

   public static function handleSearch($query, $search, $columns)
   {
      $query->where(function ($q) use ($search, $columns) {
         foreach ($columns as $index => $col) {
            $method = $index === 0 ? 'where' : 'orWhere';
            $q->{$method}($col, 'like', "%{$search}%");
         }
         // relational search
         $q->orWhereHas('projectRelatingBackTo_User', function ($uq) use ($search) {
            $uq->where('name', 'like', "%{$search}%");
         });
      });

      return $query;
   }
   // -----------------------------------------------

   public static function userBlock($user)
   {
      $avatar = $user?->avatar ?? null;
      $avatarHtml = $avatar
         ? '<img src="' . Storage::url($avatar) . '" class="w-10 h-10 rounded-full object-cover mx-auto" />'
         : '<img src="' . asset('site_assets/dummies/dummy_man.webp') . '" class="w-10 h-10 rounded-lg object-cover mx-auto opacity-70" />';

      $targetUserId = $user?->id ?? '—';
      $targetUserName = ($user?->name ?? '—') . " " . ($user?->last_name ?? null);
      $targetUserUid = $user?->user_uid ?? '—';
      $targetUserEmail = $user?->email ?? '—';

      return '
        <div class="w-48 flex items-center space-x-3 flex-nowrap">
            <div class="shrink-0">' . $avatarHtml . '</div>
            <div class="flex flex-col text-left min-w-0">
                <span class="text-xs text-gray-400">DB ID: ' . $targetUserId . '</span>
                <span class="text-sm font-medium" title="' . e($targetUserName) . '">' . Str::limit($targetUserName, 60) . '</span>
                <span class="text-xs font-light text-gray-500 dark:text-gray-300 break-all"> UID: ' . $targetUserUid . '</span>
                <span class="text-xs text-gray-400 dark:text-gray-300 break-all">' . $targetUserEmail . '</span>
            </div>
        </div>
    ';
   }
   // -----------------------------------------------

   public static function subscriberBlock($content)
   {
      $tier = $content?->subscriberAppRelatingBackTo_Tier;
      $paymentStatus = $content?->payment_status;
      $subStatus = $content?->sub_status;

      return '
         <div class="flex items-center space-x-3 flex-nowrap">
            <div class="flex flex-col text-left">
               <span class="text-xs text-gray-400">DB ID: ' . ($content?->id ?? '—') . '</span>
               <span class="text-xs font-light text-gray-500 dark:text-gray-300"> UID: ' . ($content?->rc_subscription_id ?? "-") . '</span>
               <span class="text-sm font-medium">Tier:' . ($tier ? $tier->name : "N/A") . '</span>
               <div class="flex flex-wrap gap-2">' . self::paymentEnums($paymentStatus) . self::subEnums($subStatus) . '</div>
               <span class="text-sx">Store:' . ($content?->store ?? "N/A") . '</span>
               <span class="text-sm">Amount:' . ($content?->amount ? ($content?->amount . " " . $content?->currency) : "N/A") . '</span>
            </div>
         </div>
      ';
   }
   // -----------------------------------------------

   public static function detailBlock($content, $hasUid = true, $content_uid, $hasImage = true, $image = null, ?array $extras = null)
   {
      if (is_array($content)) {
         $content = (object)$content;
      }
      $avatarHtml = $image
         ? '<img src="' . Storage::url($image) . '" class="w-10 h-10 rounded-lg   object-cover mx-auto" />'
         : '<img src="' . asset('site_assets/dummies/dummy_no_file.jpg') . '" class="w-10 h-10 rounded-lg object-cover mx-auto opacity-70" />';

      $targetUserId = $content?->id ?? '—';
      $targetUserName = ($content?->title ?? $content?->name ?? '—');
      $targetUserUid = $content_uid ?? '—';

      $hideUid = $hasUid ? null : 'hidden';
      $hideImage = $hasImage ? null : 'hidden';

      $extraBlock = '';

      if (!empty($extras)) {
         foreach ($extras as $key => $value) {
            $extraBlock .= '<span class="text-xs font-light text-gray-500 dark:text-gray-300">' . $key . ': ' . ($value ?? '—') . '</span>';
         }
      }

      return '
         <div class="flex items-center space-x-3 flex-nowrap">
            <div class="shrink-0 ' . $hideImage . '">' . $avatarHtml . '</div>
            <div class="flex flex-col text-left">
               <span class="text-xs text-gray-400">DB ID: ' . $targetUserId . '</span>
               <span class="text-sm font-medium">' . $targetUserName . '</span>
               <span class="text-xs font-light text-gray-500 dark:text-gray-300 ' . $hideUid . '"> UID: ' . $targetUserUid . '</span>'
         . $extraBlock .
         '</div>
         </div>
      ';
   }
   // -----------------------------------------------

   public static function roleColor($role)
   {
      $map = [
         'super_admin' => 'bg-blue-300 opacity-75',
         'admin' => 'bg-emerald-300 opacity-75',
         'enrolled' => 'bg-fuchsia-300 opacity-75',
         'guest' => 'bg-orange-300 opacity-75',
      ];

      $key = $role ? strtolower($role) : null;
      $color = $key && isset($map[$key]) ? $map[$key] : 'bg-gray-400';
      $text = $key ? ucfirst($key) : 'Unverified';

      return '<span class="px-2 py-1 text-xs font-medium rounded ' . $color . ' text-gray-800">' . $text . '</span>';
   }
   // -----------------------------------------------

   public static function phaseColor($phase, $size = null)
   {
      $phaseColor = [
         'male' => 'bg-indigo-300 opacity-75',
         'female' => 'bg-rose-300 opacity-75',
         'prefer_not' => 'bg-green-300 opacity-75',
         // -------------------------
         '3' => 'bg-green-200 opacity-75',
         '7' => 'bg-orange-200 opacity-75',
         '14' => 'bg-blue-200 opacity-75',
         '30' => 'bg-rose-200 opacity-75',
         // -------------------------
         'on-track' => 'bg-cyan-400 opacity-75',
         'cancel_scheduled' => 'bg-orange-400 opacity-75',
         'cancelled' => 'bg-fuchsia-400 opacity-75',
         'payment_failed' => 'bg-red-400 opacity-75',
         // -------------------------
         'pending' => 'bg-orange-400 opacity-75',
         'processing' => 'bg-green-400 opacity-75',
         'completed' => 'bg-blue-400 opacity-75',
         // -------------------------
         'unpaid' => 'bg-rose-400 opacity-75',
         'paid' => 'bg-violet-400 opacity-75',
         'on_hold' => 'bg-violet-300 opacity-75',
         // -------------------------
         'color' => 'bg-violet-300 opacity-75',
         'size' => 'bg-emerald-300 opacity-75',
         'flavor' => 'bg-rose-300 opacity-75',
         'quantity' => 'bg-orange-300 opacity-75',
      ];

      $textSize = $size ?? 'text-xs';

      $matchPhase = $phase ? strtolower((string)$phase) : 'N/A';
      $color = $phaseColor[$matchPhase] ?? null;

      if (!$color) {
         foreach ($phaseColor as $key => $value) {
            if ($key !== '' && str_contains($matchPhase, $key)) {
               $color = $value;
               break;
            }
         }
      }

      $color = $color ?? 'bg-gray-400';

      return '<span class="px-2 py-1 ' . $textSize . ' font-medium rounded ' . $color . '  text-gray-800">' . ucfirst($matchPhase) . '</span>';
   }
   // -----------------------------------------------

   public static function subEnums($subStatus, $size = null)
   {
      $subStatusColor = [
         'requested' => 'bg-slate-300 opacity-75',
         'running' => 'bg-emerald-300 opacity-75',
         'scheduled' => 'bg-cyan-300 opacity-75',
         'paused' => 'bg-amber-300 opacity-75',
         'non_renewing' => 'bg-violet-300 opacity-75',
         'billing_issue' => 'bg-rose-300 opacity-75',
         'product_change' => 'bg-fuchsia-300 opacity-75',
         'cancelled' => 'bg-gray-300 opacity-75',
         'expired' => 'bg-zinc-300 opacity-75',
      ];

      $textSize = $size ?? 'text-xs';

      if ($subStatus instanceof \BackedEnum) {
         $matchStatus = strtolower((string)$subStatus->value);
         $label = method_exists($subStatus, 'label') ? $subStatus->label() : ucfirst($matchStatus);
      } else {
         $matchStatus = $subStatus ? strtolower((string)$subStatus) : 'n/a';
         $label = ucfirst($matchStatus);
      }

      $color = $subStatusColor[$matchStatus] ?? 'bg-gray-400';

      return '<span class="px-2 py-1 ' . $textSize . ' font-medium rounded ' . $color . ' text-gray-800">' . $label . '</span>';
   }
   // -----------------------------------------------

   public static function orderEnums($trxPhase, $size = null)
   {
      $orderPhaseColor = [
         'pending' => 'bg-yellow-300 opacity-75',
         'processing' => 'bg-sky-300 opacity-75',
         'completed' => 'bg-emerald-300 opacity-75',
         'delivered' => 'bg-emerald-300 opacity-75',
         'cancelled' => 'bg-orange-300 opacity-75',
         'on_hold' => 'bg-violet-300 opacity-75',
         'failed' => 'bg-red-300 opacity-75',
      ];

      $textSize = $size ?? 'text-xs';

      if ($trxPhase instanceof \BackedEnum) {
         $matchPhase = strtolower((string)$trxPhase->value);
         $label = method_exists($trxPhase, 'label') ? $trxPhase->label() : ucfirst($matchPhase);
      } else {
         $matchPhase = $trxPhase ? strtolower((string)$trxPhase) : 'n/a';
         $label = ucfirst($matchPhase);
      }

      $color = $orderPhaseColor[$matchPhase] ?? 'bg-gray-400';

      return '<span class="px-2 py-1 ' . $textSize . ' font-medium rounded ' . $color . ' text-gray-800">' . $label . '</span>';
   }
   // -----------------------------------------------

   public static function trxEnums($trxPhase, $size = null)
   {
      $trxPhaseColor = [
         'initiated' => 'bg-violet-300 opacity-75',
         'completed' => 'bg-blue-300 opacity-75',
         'settled' => 'bg-emerald-300 opacity-75',
         'cancelled' => 'bg-orange-300 opacity-75',
         'failed' => 'bg-red-300 opacity-75',
      ];

      $textSize = $size ?? 'text-xs';

      if ($trxPhase instanceof \BackedEnum) {
         $matchPhase = strtolower((string)$trxPhase->value);
         $label = method_exists($trxPhase, 'label') ? $trxPhase->label() : ucfirst($matchPhase);
      } else {
         $matchPhase = $trxPhase ? strtolower((string)$trxPhase) : 'n/a';
         $label = ucfirst($matchPhase);
      }

      $color = $trxPhaseColor[$matchPhase] ?? 'bg-gray-400';

      return '<span class="px-2 py-1 ' . $textSize . ' font-medium rounded ' . $color . ' text-gray-800">' . $label . '</span>';
   }
   // -----------------------------------------------

   public static function paymentEnums($paymentStatus, $size = null)
   {
      $paymentStatusColor = [
         'unpaid' => 'bg-rose-300 opacity-75',
         'paid' => 'bg-blue-300 opacity-75',
         'pending' => 'bg-amber-300 opacity-75',
         'failed' => 'bg-red-300 opacity-75',
         'refunded' => 'bg-sky-300 opacity-75',
         'partially_refunded' => 'bg-cyan-300 opacity-75',
      ];

      $textSize = $size ?? 'text-xs';

      if ($paymentStatus instanceof \BackedEnum) {
         $matchStatus = strtolower((string)$paymentStatus->value);
         $label = method_exists($paymentStatus, 'label') ? $paymentStatus->label() : ucfirst($matchStatus);
      } else {
         $matchStatus = $paymentStatus ? strtolower((string)$paymentStatus) : 'n/a';
         $label = ucfirst($matchStatus);
      }

      $color = $paymentStatusColor[$matchStatus] ?? 'bg-gray-400';

      return '<span class="px-2 py-1 ' . $textSize . ' font-medium rounded ' . $color . ' text-gray-800">' . $label . '</span>';
   }

   // -----------------------------------------------

   public static function notifyColor($phase, $size = null)
   {
      $phaseColor = [
         'login' => 'bg-indigo-300 opacity-75',
         'order' => 'bg-indigo-300 opacity-75',
         'system' => 'bg-emerald-300 opacity-75',
         'message' => 'bg-violet-300 opacity-75',
         'payment' => 'bg-blue-300 opacity-75',
         'update' => 'bg-green-300 opacity-75',
         // -------------------------
         'info' => 'bg-cyan-400 opacity-75',
         'alert' => 'bg-yellow-400 opacity-75',
         'normal' => 'bg-emerald-400 opacity-75',
         'warning' => 'bg-orange-400 opacity-75',
         'critical' => 'bg-red-400 opacity-75',
      ];

      $textSize = $size ?? 'text-xs';

      $matchPhase = strtolower($phase);
      $color = $phaseColor[$matchPhase] ?? 'bg-gray-400';

      return '<span class="px-2 py-1 ' . $textSize . ' font-medium rounded ' . $color . '  text-gray-800">' . ucfirst($matchPhase) . '</span>';
   }
   // -----------------------------------------------

   public static function categoryColor($category, $size = null)
   {
      $phaseColor = [
         'supplements' => 'bg-violet-300 opacity-75',
         'apparel' => 'bg-fuchsia-300 opacity-75',
         'equipment' => 'bg-indigo-300 opacity-75',
         'digital products' => 'bg-orange-300 opacity-75',
         'dietry' => 'bg-green-300 opacity-75',
      ];

      $textSize = $size ?? 'text-xs';

      $matchPhase = strtolower($category);
      $color = $phaseColor[$matchPhase] ?? 'bg-yellow-400';

      return '<span class="px-2 py-1 ' . $textSize . ' font-medium rounded ' . $color . '  text-gray-800">' . ucfirst($matchPhase) . '</span>';
   }
}
// -----------------------------------------------

/*
USE CASE-1: success + error
   try {
      return DatatableHelper::handleDatatableSuccess($draw, $recordsTotal, $recordsFiltered, $data);
   } catch (Throwable $e) {
      return DatatableHelper::handleDatatableError($request, $e, true, "Workmen jobs datatable error");
   }

USE CASE-2: action + row
   $row->actions = DatatableHelper::datatableAction($row, false, true, 'backend_issue_toggle', 'backend_issue_delete');

USE CASE-3: Row-click behaviour 
   $row->actions = ... ... ...
   $row->DT_RowAttr = DatatableHelper::rowClickBehaviour($row->view_url, 'page');
   $row->DT_RowAttr = DatatableHelper::rowClickBehaviour($row->view_url, 'modal', 'datatableViewModalTrigger', 'datatableViewModalContent');


USE CASE-4: user
   $row->merchant_info = DatatableHelper::userBlock($row->productRelatingBackTo_Merchant);

USE CASE-5: detail
   $row->detail = DatatableHelper::detailBlock($content, $hasUid = true, $content_uid, $hasImage = true, $image);
   $row->detail = DatatableHelper::detailBlock($content, $hasUid = true, $content_uid, $hasImage = true, $image, ['key1' => 'value1']);

USE CASE-6: phase
   $row->role = DatatableHelper::phaseColor($role);
*/

