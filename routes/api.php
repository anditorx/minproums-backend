<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductDuaController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\TransactionDuaController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function(){
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('user/photo', [UserController::class, 'updatePhoto']);
    Route::post('logout/{id}', [UserController::class, 'logout']);

    Route::get('transaction', [TransactionController::class, 'all']);
    Route::post('transaction/addToCart', [TransactionController::class, 'addToCart']);
    Route::get('transaction/getItemCart', [TransactionController::class, 'getItemCart']);
    Route::post('transaction/deletItemOnCart', [TransactionController::class, 'deletItemOnCart']);
    Route::post('transaction/updateAddQty', [TransactionController::class, 'updateAddQty']);
    Route::post('transaction/updateMinusQty', [TransactionController::class, 'updateMinusQty']);
    Route::post('transaction/updateStatusPayment', [TransactionController::class, 'updateStatusPayment']);
    Route::post('transaction/getTotalPrice', [TransactionController::class, 'getTotalPrice']);
    Route::post('transaction/updateStatusPaymentConfirm', [TransactionController::class, 'updateStatusPaymentConfirm']);
    Route::post('transaction/{id}', [TransactionController::class, 'update']);
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::get('product', [ProductController::class, 'all']);

Route::get('transactionDua', [TransactionDuaController::class, 'all']);
Route::get('productDua', [ProductDuaController::class, 'all']);
Route::post('transactionDua/addToCart', [TransactionDuaController::class, 'addToCart']);
Route::get('transactionDua/getCart', [TransactionDuaController::class, 'getCart']);
Route::post('transactionDua/updateCart', [TransactionDuaController::class, 'updateCart']);
