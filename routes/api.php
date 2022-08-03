<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//public routes

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/follows/{id?}', [UserController::class, 'follows']);


//protected routes

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::post('/logout', [UserController::class, 'logout']);
});


