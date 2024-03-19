<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\TransactionController;
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
//auth
Route::get('/', function(){
    return response()->json([
        'success' => false,
        'message' => 'Silahkan login terlebih dahulu',
    ],401);
})->name('login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//kategori
Route::get('/kategori',[CategoryController::class, 'index'])->middleware('auth:sanctum');
Route::get('/kategori/{id}',[CategoryController::class, 'show'])->middleware('auth:sanctum');
Route::post('/kategori',[CategoryController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/kategori/{id}',[CategoryController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/kategori/{id}',[CategoryController::class, 'destroy'])->middleware('auth:sanctum');
//barang
Route::post('/item',[ItemController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/item/{id}',[ItemController::class, 'update'])->middleware('auth:sanctum');
Route::get('/item',[ItemController::class, 'index'])->middleware('auth:sanctum');
Route::get('/item/{id}',[ItemController::class, 'show'])->middleware('auth:sanctum');
Route::delete('/item/{id}',[ItemController::class, 'destroy'])->middleware('auth:sanctum');
//transaksi
Route::get('/transaction/history',[TransactionController::class, 'index'])->middleware('auth:sanctum');
Route::get('/transaction/detail/{id}',[TransactionController::class, 'show'])->middleware('auth:sanctum');
Route::post('/transaction',[TransactionController::class, 'store'])->middleware('auth:sanctum');
Route::post('/transaction/checkout',[TransactionController::class, 'checkout'])->middleware('auth:sanctum');

