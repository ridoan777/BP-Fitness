<?php

use App\Domains\Community_forums\Controllers\Categories\ApiForumCategoryController;
use App\Domains\Community_forums\Controllers\Groups\ApiForumGroupController;
use App\Domains\Community_forums\Controllers\Memberships\ApiForumMemberController;
use App\Domains\Community_forums\Controllers\Messages\ApiForumMessageController;
use Illuminate\Support\Facades\Route;

// ------------ HOMEPAGE ------------

Route::prefix('forum')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/home', [ApiForumCategoryController::class, 'home']);
});
// ------------ HOMEPAGE ------------

// ------------ FORUM CATEGORIES ------------

Route::prefix('forum')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/categories/index', [ApiForumCategoryController::class, 'index']);
   Route::get('/categories/{category}/show', [ApiForumCategoryController::class, 'show']);
});
// ------------ FORUM CATEGORIES ------------

// ------------ FORUM MEMBERSHIPS ------------

Route::prefix('forum')->middleware(['auth:sanctum'])->group(function () {

   Route::post('/membership/group/{group}/store', [ApiForumMemberController::class, 'store']);
   Route::get('/membership/group/{group}/members', [ApiForumMemberController::class, 'members']);
});
// ------------ FORUM MEMBERSHIPS ------------


// ------------ FORUM GROUPS ------------

Route::prefix('forum')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/groups/view/my-groups', [ApiForumGroupController::class, 'view']);
   Route::get('/groups/discover/other-groups', [ApiForumGroupController::class, 'discover']);
});
// ------------ FORUM GROUPS ------------


// ------------ FORUM MESSAGES ------------

Route::prefix('forum')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/message/group/{group}/all-messages', [ApiForumMessageController::class, 'index']);

   Route::post('/message/send', [ApiForumMessageController::class, 'send']);
   Route::post('/message/{message}/report', [ApiForumMessageController::class, 'report']);
   
   Route::delete('/message/{message}/delete', [ApiForumMessageController::class, 'delete']);

});
// ------------ FORUM MESSAGES ------------