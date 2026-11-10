<?php

use App\Http\Controllers\Workflows\Ai\agentic\WebAiAgentController;
use App\Http\Controllers\Workflows\Ai\WebAiController;
use Illuminate\Support\Facades\Route;


// ------------ PROJECTS ------------

Route::prefix('admin')->middleware(['auth:web'])->group(function () {

   // Route::get('/ai/index', [WebAiController::class, 'index'])->name('backend_ai_index');

   // Route::get('/ai/send', [WebAiController::class, 'send']);
});
// ------------ PROJECTS ------------


// ------------ SDK AGENTS ------------

Route::prefix('admin')->group(function () {

   // Route::get('/ai/agent/call', [WebAiAgentController::class, 'callAgent'])->name('backend_ai_agent');

});
// ------------ SDK AGENTS ------------