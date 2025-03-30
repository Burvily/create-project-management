<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/recent-activity', [DashboardController::class, 'getRecentActivity']);
    Route::get('/dashboard/project-metrics', [DashboardController::class, 'getProjectMetrics']);
    Route::get('/dashboard/upcoming-milestones', [DashboardController::class, 'getUpcomingMilestones']);
    
    // Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
    
    // Project specific endpoints
    Route::get('/projects/{project}/wbs', [ProjectController::class, 'getWorkBreakdownStructure']);
    Route::get('/projects/{project}/budget', [ProjectController::class, 'getBudget']);
    Route::get('/projects/{project}/timeline', [ProjectController::class, 'getTimeline']);
    
    // Tasks
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    
    // Milestones
    Route::get('/milestones', [MilestoneController::class, 'index']);
    Route::post('/milestones', [MilestoneController::class, 'store']);
    Route::get('/milestones/{milestone}', [MilestoneController::class, 'show']);
    Route::put('/milestones/{milestone}', [MilestoneController::class, 'update']);
    
    // User management
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
});