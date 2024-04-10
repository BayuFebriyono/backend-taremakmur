<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BarangController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::get('user', [AuthController::class, 'me']);
Route::post('logout', [AuthController::class, 'logout']);

// Route Barang
Route::get('barangs', [BarangController::class, 'all'])->middleware('jwt.verify');
Route::get('barangs/{kode}', [BarangController::class, 'searchBarang'])->middleware('jwt.verify');
Route::get('barang/{id}', [BarangController::class, 'getById'])->middleware('jwt.verify');
