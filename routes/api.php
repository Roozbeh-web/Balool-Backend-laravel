<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//public routes

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


//protected routes

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::get('/protected', [UserController::class, 'index']);
});


