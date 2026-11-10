<?php

use App\Domains\Community_forums\Controllers\Categories\WebForumCategoryController;
use App\Domains\Community_forums\Controllers\Groups\WebForumGroupController;
use App\Domains\Community_forums\Controllers\Messages\WebForumMessageController;
use Illuminate\Support\Facades\Route;


// ------------ FORUM CATEGORIES ------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {
   Route::get('/forums/category/index', [WebForumCategoryController::class, 'index'])->name('backend_forum_category_index');

   Route::get('/forums/category/{singleCategory}/{slug}/show', [WebForumCategoryController::class, 'show'])->name('backend_forum_category_show');

   Route::post('/forums/category/store', [WebForumCategoryController::class, 'store'])->name('backend_forum_category_store');

   Route::post('/forums/category/{singleCategory}/toggle', [WebForumCategoryController::class, 'toggle'])->name('backend_forum_category_toggle');

   Route::delete('/forums/category/{singleCategory}/delete', [WebForumCategoryController::class, 'delete'])->name('backend_forum_category_delete');

   Route::post('/forums/category/datatable', [WebForumCategoryController::class, 'datatable'])->name('backend_forum_category_datatable');
});
// ------------ FORUM CATEGORIES ------------

// ------------ FORUM GROUPS ------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {
   Route::get('/forums/group/index', [WebForumGroupController::class, 'index'])->name('backend_forum_group_index');

   Route::get('/forums/group/{singleGroup}/{slug}/show', [WebForumGroupController::class, 'show'])->name('backend_forum_group_show');

   Route::post('/forums/group/store', [WebForumGroupController::class, 'store'])->name('backend_forum_group_store');

   Route::post('/forums/group/{singleGroup}/toggle', [WebForumGroupController::class, 'toggle'])->name('backend_forum_group_toggle');

   Route::delete('/forums/group/{singleGroup}/delete', [WebForumGroupController::class, 'delete'])->name('backend_forum_group_delete');

   Route::post('/forums/group/bulk-delete', [WebForumGroupController::class, 'bulkDelete'])->name('backend_forum_group_bulk_delete');

   Route::post('/forums/group/datatable', [WebForumGroupController::class, 'datatable'])->name('backend_forum_group_datatable');
});
// ------------ FORUM GROUPS ------------

// ------------ FORUM EXPANDED VIEW ------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::get('/forums/group/{singleGroup}/{slug}/expand', [WebForumMessageController::class, 'expand'])->name('backend_forum_group_expand');

   Route::get('/forums/group/message/{message}/report', [WebForumMessageController::class, 'report'])->name('backend_forum_group_report');

   Route::get('/forums/group/{group}/messages/older', [WebForumMessageController::class, 'fetchOlder'])->name('backend_forum_group_messages_older');

   Route::delete('/message/{message}/delete', [WebForumMessageController::class, 'delete'])->name('backend_forum_message_delete');
});
// ------------ FORUM EXPANDED VIEW ------------
