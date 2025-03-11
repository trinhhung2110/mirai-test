<?php

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/users/{id}', [\App\Http\Controllers\UserController::class, 'findUserId']);

// test 1
Route::apiResource('accounts', AccountController::class);

// test 2
Route::post('/showSerialpaso', [AccountController::class, 'showSerialpaso']);

// test 3
Route::get('/countStudents', [AccountController::class, 'countStudents']);

// test 4
Route::get('/top20Percent/{numPeople}', [AccountController::class, 'findTop20Percent']);

// test 5
Route::get('/findFurthestPeople', [AccountController::class, 'findFurthestPeople']);


