<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\SubjectController;
use App\Http\Controllers\api\v1\SubjectUsersController;

Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');

// Route::middleware(["role:admin"])->name("admin.")->prefix("api/admin")->group(function(){
//     Route::apiResource('/subject', SubjectController::class);
//     Route::apiResource('/user', UserController::class) ;
// });

Route::prefix('subjectUsers/{subject}/')->controller(SubjectUsersController::class)-> group(function () {
    Route::post('/',"store")->name('subjectUsers.store');
    Route::put('/{user}',"update")->name("subjectUsers.update");
    Route::delete('/{user}',"destroy")->name("subjectUsers.destroy");
});
require __DIR__.'/auth.php';
