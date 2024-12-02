<?php

use App\Models\Subject;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\SubjectController;


Route::get('/user', function (Request $request) {
    return response()->json([
        "user" => $request->user(), // Authenticated user details
        "roles" => $request->user()->roles()->get() // Fetch the actual roles from the database
    ]);
})->middleware('auth:sanctum')->name("api.user");
Route::resource('subject', SubjectController::class)->middleware('auth:sanctum');
