<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['jwt.verify:admin,kasir']], function() {
    route::get('login/check', [AuthController::class, 'loginCheck']);
    route::post('/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['jwt.verify:admin']], function() {
});
route::post('user/tambah', [UserController::class, 'store']);