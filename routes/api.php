<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\{
    AuthController,
    CustomerProductController,
    CategoryController,
    SalerProductController
};

// Authentication
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('resend-verification', [AuthController::class, 'sendVerificationCode']) -> middleware('auth');
Route::post('verify-email', [AuthController::class, 'verifyEmail']) -> middleware('auth');
Route::post('logout', [AuthController::class, 'logout']) -> middleware('auth');

// Customer product
Route::get('product', [CustomerProductController::class, 'index']);
Route::get('product/search', [CustomerProductController::class, 'search']);
Route::get('product/search/all', [CustomerProductController::class, 'searchAll']);
Route::get('product/favourites', [CustomerProductController::class, 'favourites']);
Route::get('product/cart', [CustomerProductController::class, 'cart']);
Route::patch('product/cart/{productID}', [CustomerProductController::class, 'removeFromCart']);
Route::get('product/cart-count', [CustomerProductController::class, 'cartCount']);
Route::get('product/{productID}', [CustomerProductController::class, 'getProduct']);
Route::post('product/payment/cart', [CustomerProductController::class, 'cartPayment']);
Route::post('product/payment/cart/success', [CustomerProductController::class, 'paymentCallbackSuccess']);
Route::get('product/purchases/orders', [CustomerProductController::class, 'orders']);
// Route::post('product/payment/cart/faild', [CustomerProductController::class, 'paymentCallbackFaild']);
Route::post('product/{productID}/toggle-fav', [CustomerProductController::class, 'addToFav']);
Route::post('product/{productID}/toggle-cart', [CustomerProductController::class, 'addToCart']);
Route::patch('product/{productID}/update-quantity', [CustomerProductController::class, 'updateQuantity']);

// Category
Route::get('category', [CategoryController::class, 'index']);

// Profile Saler Product
Route::get('profile/product', [SalerProductController::class, 'index']);
Route::post('profile/product', [SalerProductController::class, 'store']);
Route::get('profile/product/orders', [SalerProductController::class, 'getOrders']);
Route::put('profile/product/orders/{orderID}', [SalerProductController::class, 'delivered']);
Route::delete('profile/product/{productID}/delete', [SalerProductController::class, 'destroy']);

// Payment
Route::get('payment', function(Request $request) {
    return dd($request);
});


