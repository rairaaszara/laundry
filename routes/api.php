<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\DashboardController;


    Route::post('login',[AuthController::class,'login']);
    Route::post('register', [AuthController::class, 'store']);


Route::group(['middleware'=> ['jwt.verify:admin,kasir,owner']], function() {
    Route::get('login/check', [AuthController::class, 'logincheck']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('report', [TransaksiController::class, 'report']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

//khusus admin
Route::group(['middleware' => ['jwt.verify:admin']], function() {

    //OUTLET
    Route::get('outlet', [OutletController::class, 'getAll']);
    Route::get('outlet/{id}', [OutletController::class, 'getById']);
    Route::post('outlet', [OutletController::class, 'store']);
    Route::put('outlet/{id}', [OutletController::class, 'update']);
    Route::delete('outlet/{id}', [OutletController::class, 'delete']);

    //PAKET
    Route::post('paket', [PaketController::class, 'store']);
    Route::get('paket', [PaketController::class, 'getAll']);
    Route::get('paket/{id}', [PaketController::class, 'getById']);
    Route::put('paket/{id}', [PaketController::class, 'update']);
    Route::delete('paket/{id}', [PaketController::class, 'delete']);

    //USER
    Route::post('user', [UserController::class, 'store']);
    Route::get('user', [UserController::class, 'getAll']);
    Route::get('user/{id}', [UserController::class, 'getById']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'delete']);
});

//khusus admin dan kasir 
Route::group(['middleware' => ['jwt.verify:admin,kasir']], function() {
  
    //member  
    Route::post('member', [MemberController::class, 'store']);
    Route::get('member', [MemberController::class, 'getAll']);
    Route::get('member/{id}', [MemberController::class, 'getById']);
    Route::put('member/{id}', [MemberController::class, 'update']);
    Route::delete('member/{id}', [MemberController::class, 'delete']);

    //TRANSAKSI
    Route::post('transaksi', [TransaksiController::class, 'store']);
    Route::get('transaksi/{id}', [TransaksiController::class, 'getById']);
    Route::get('transaksi', [TransaksiController::class, 'getAll']);
    Route::put('transaksi/{id}', [TransaksiController::class, 'update']);

    //DETAIL TRANSAKSI
    Route::post('transaksi/detail/tambah', [DetailTransaksiController::class, 'store']);
    Route::get('transaksi/detail/{id}', [DetailTransaksiController::class, 'getById']);
    Route::post('transaksi/status/{id}', [TransaksiController::class, 'changeStatus']);
    Route::post('transaksi/bayar/{id}', [TransaksiController::class, 'bayar']);
    Route::get('transaksi/total/{id}', [DetailTransaksiController::class, 'getTotal']);    
   
});
