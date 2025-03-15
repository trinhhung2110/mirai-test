<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/users/{id}', [\App\Http\Controllers\UserController::class, 'findUserId']);

// test 1
Route::apiResource('accounts', AccountController::class);

// test 2
Route::post('/showSerialpaso', [TestController::class, 'showSerialpaso']);

// test 3
Route::get('/countStudents', [TestController::class, 'countStudents']);

// test 4
Route::post('/top20Percent', [TestController::class, 'findTop20Percent']);

// test 5
Route::post('/findFurthestPeople', [TestController::class, 'findFurthestPeople']);


