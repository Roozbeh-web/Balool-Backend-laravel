<?php

use App\Http\Controllers\ToggleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//public routes

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/login', [UserController::class, 'getLogin'])->name('login');


//protected routes

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::get('/follows', [UserController::class, 'getFollows']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::put('/update', [UserController::class, 'update']);
    Route::post('/follow', [UserController::class, 'follow']);

    Route::post('/toggle', [ToggleController::class, 'toggle']);
});


