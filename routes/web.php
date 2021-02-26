<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Brand;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProductController::class, 'index']);
Route::get('/checkout', [OrderController::class, 'checkoutPage']);
Route::post('/checkout', [OrderController::class, 'checkout']);

/*
 * Ajax Requests
 */
Route::post('/add-to-cart', [OrderController::class, 'addProductToCart']);
Route::post('/update-shipping', [OrderController::class, 'updateShippingCost']);
Route::post('/render-order-table',[OrderController::class,'renderCheckoutTable']);

