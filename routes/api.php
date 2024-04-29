<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
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

// Route Customer
Route::get('customers', [CustomerController::class, 'all'])->middleware('jwt.verify');
Route::get('customer/{id}', [CustomerController::class, 'getById'])->middleware('jwt.verify');

// Route Order
Route::post('order',[OrderController::class, 'createOrder'])->middleware('jwt.verify');
Route::get('/list-order', [OrderController::class, 'listOrder'])->middleware('jwt.verify');

// Generate Pdf
Route::get('/generate-pdf/{no_invoice}',[OrderController::class, 'generatePdf'])->middleware('jwt.verify');
