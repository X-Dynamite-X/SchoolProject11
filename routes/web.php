<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\SubjectController;

Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');

// Route::middleware(["role:admin"])->name("admin.")->prefix("api/admin")->group(function(){
//     Route::apiResource('/subject', SubjectController::class);
//     Route::apiResource('/user', UserController::class) ;
// });


require __DIR__.'/auth.php';
