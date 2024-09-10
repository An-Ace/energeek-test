<?php
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']); // Get all tasks
    Route::get('/{id}', [UserController::class, 'show']); // Get task by ID
    Route::post('/', [UserController::class, 'store']); // Create a new task
    Route::put('/{id}', [UserController::class, 'update']); // Update a task
    Route::delete('/{id}', [UserController::class, 'destroy']); // Delete a task
});
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']); // Get all categories
    Route::get('/{id}', [CategoryController::class, 'show']); // Get category by ID
    Route::post('/', [CategoryController::class, 'store']); // Create a new category
    Route::put('/{id}', [CategoryController::class, 'update']); // Update a category
    Route::delete('/{id}', [CategoryController::class, 'destroy']); // Delete a category
});
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']); // Get all tasks
    Route::get('/{id}', [TaskController::class, 'show']); // Get task by ID
    Route::post('/', [TaskController::class, 'store']); // Create a new task
    Route::put('/{id}', [TaskController::class, 'update']); // Update a task
    Route::delete('/{id}', [TaskController::class, 'destroy']); // Delete a task
});
Route::get('/my-tasks/{email}', [UserController::class, 'getMyTasks']); // Get all tasks
Route::post('/add-tasks', [TaskController::class, 'add_tasks']); // Get all tasks
Route::post('/login', [UserController::class, 'login']); // Get all tasks

Route::get('{any}', function () {
    return response()->json([
        'error' => true,
        'message' => 'API not found',
        'redirect' => ''
    ], 404);
})->where('any', '.*');