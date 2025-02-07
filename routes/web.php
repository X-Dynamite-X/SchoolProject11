<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\SubjectController;
use App\Http\Controllers\api\v1\ConversationController;
use App\Http\Controllers\api\v1\SubjectUsersController;

Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');


require __DIR__.'/auth.php';
