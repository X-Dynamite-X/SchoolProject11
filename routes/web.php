<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\SubjectController;
use App\Http\Controllers\api\v1\ConversationController;
use App\Http\Controllers\api\v1\SubjectUsersController;

// Broadcasting routes
Broadcast::routes(['middleware' => ['auth:sanctum']]);



Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');


require __DIR__.'/auth.php';
