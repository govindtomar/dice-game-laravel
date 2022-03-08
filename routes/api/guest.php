<?php

use App\Http\Controllers\API\V1\Front\TaskController;

Route::prefix('fv1')->middleware(['guest:sanctum'])->group(function () {
    
    Route::get('task', [TaskController::class, 'show']);

});

