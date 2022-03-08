<?php 

use App\Http\Controllers\API\V1\Admin\UserController;
use App\Http\Controllers\API\V1\Admin\RoleController;
use App\Http\Controllers\API\V1\Admin\PermissionController;
use App\Http\Controllers\API\V1\Admin\RolePermissionController;
use App\Http\Controllers\API\V1\Admin\ChatController;
use App\Http\Controllers\API\V1\Admin\TaskController;
use App\Http\Controllers\API\V1\Admin\CategoryController;


Route::prefix('bv1')->middleware(['auth:sanctum'])->group(function () {
	Route::get('/dashboard', [UserController::class, 'index'])->name('index');

	Route::prefix('user')->name('user.')->group(function () {
		Route::get('/', [UserController::class, 'index'])->name('index');
		Route::post('/create', [UserController::class, 'store'])->name('create');
		Route::post('/update', [UserController::class, 'update'])->name('update');
		Route::post('/delete', [UserController::class, 'delete'])->name('delete');
		Route::post('/status', [UserController::class, 'changeStatus'])->name('status');
		Route::get('/{id}', [UserController::class, 'show'])->name('show');
	});

	Route::prefix('role')->name('role.')->group(function () {
		Route::get('/', [RoleController::class, 'index'])->name('index');
		Route::post('/create', [RoleController::class, 'store'])->name('create');
		Route::post('/update', [RoleController::class, 'update'])->name('update');
		Route::post('/delete', [RoleController::class, 'delete'])->name('delete');
		Route::post('/status', [RoleController::class, 'changeStatus'])->name('status');
		Route::get('/{id}', [RoleController::class, 'show'])->name('show');
	});

	Route::prefix('permission')->name('permission.')->group(function () {
		Route::get('/', [PermissionController::class, 'index'])->name('index');
		Route::post('/create', [PermissionController::class, 'store'])->name('create');
		Route::post('/update', [PermissionController::class, 'update'])->name('update');
		Route::post('/delete', [PermissionController::class, 'delete'])->name('delete');
		Route::post('/status', [PermissionController::class, 'changeStatus'])->name('status');
		Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
	});

	Route::prefix('chat')->name('permission.')->group(function () {
		Route::get('/', [ChatController::class, 'index'])->name('index');
	});

	Route::prefix('role-permission')->name('role-permission.')->group(function () {
		Route::get('/{slug}', [RolePermissionController::class, 'role_permission_index'])->name('index');
		Route::post('/update', [RolePermissionController::class, 'role_permission_update'])->name('update');
	});

	Route::prefix('task')->name('task.')->group(function () {
		Route::get('/', [TaskController::class, 'index'])->name('index');
		Route::post('/create', [TaskController::class, 'store'])->name('create');
		Route::post('/update', [TaskController::class, 'update'])->name('update');
		Route::post('/delete', [TaskController::class, 'destroy'])->name('delete');
		Route::post('/status', [TaskController::class, 'changeStatus'])->name('status');
		Route::get('/{id}', [TaskController::class, 'show'])->name('show');
	});

	Route::prefix('category')->name('category.')->group(function () {
		Route::get('/', [CategoryController::class, 'index'])->name('index');
		Route::post('/create', [CategoryController::class, 'store'])->name('create');
		Route::post('/update', [CategoryController::class, 'update'])->name('update');
		Route::post('/delete', [CategoryController::class, 'destroy'])->name('delete');
		Route::post('/status', [CategoryController::class, 'changeStatus'])->name('status');
		Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
	});

	Route::get('routes', [RolePermissionController::class, 'routes']);

});