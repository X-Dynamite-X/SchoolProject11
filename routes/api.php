<?php

use App\Models\Subject;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\SubjectController;
use App\Http\Controllers\api\v1\SubjectUsersController;
use App\Http\Controllers\api\v1\ConversationController;



Route::get('/user', function (Request $request) {
    return response()->json([
        "user" => $request->user()->load(["subjects","roles"]), // Authenticated user details
          // Fetch the actual roles from the database
        //   "roles" => $request->user()->roles()->get(),
    ]);
})->middleware('auth:sanctum')->name("api.user");

Route::resource('/conversation', ConversationController::class);

Route::middleware(["role:admin", "auth:sanctum", "auth"])->name("admin.")->prefix("admin")->group(function () {

    Route::resource('/subject', SubjectController::class);
    Route::resource('/user', UserController::class);
    Route::prefix('subjectUsers/{subject}/')->controller(SubjectUsersController::class)-> group(function () {
        Route::post('/',"store")->name('subjectUsers.store');
        Route::put('/{user}',"update")->name("subjectUsers.update");
        Route::delete('/{user}',"destroy")->name("subjectUsers.destroy");
    });
});
