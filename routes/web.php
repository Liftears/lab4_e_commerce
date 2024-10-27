<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication routes
Auth::routes();

// Public product routes accessible to everyone
Route::group(['middleware' => 'non-admin'], function () {
    Route::get('/', [GuestController::class, 'index'])->name('guest.home');
    Route::get('/guest/product/{id}', [GuestController::class, 'show'])->name('guest.show');
    Route::get('/guest/index', [GuestController::class, 'index'])->name('guest.index');
    Route::get('/guest/category/{id}', [GuestController::class, 'filterByCategory'])->name('guest.category');
});

// Product resource routes with admin access control
Route::middleware(['role:admin'])->group(function () {
    Route::resource('products', ProductController::class)->except(['index', 'show']);

    // Admin view of products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/category/{id}', [ProductController::class, 'filterByCategory'])->name('products.category');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});

// Admin routes for managing users and categories
Route::group(['middleware' => 'role:admin'], function () {
    Route::resource('users', UserController::class);
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::post('/admin/orders/{order}/updateStatus', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('/admin/orders/reports', [OrderController::class, 'downloadReport'])->name('admin.orders.downloadReport');

});


Route::group(['middleware' => 'role:customer'], function () {
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/guest/cart', [CartController::class, 'view'])->name('guest.cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/payment/success', [CheckoutController::class, 'paymentSuccess'])->name('checkout.payment.success');
    Route::get('/checkout/payment/cancel', [CheckoutController::class, 'paymentCancel'])->name('checkout.payment.cancel');

    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my.orders');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('my.orders.show');


});

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateUser'])->name('profile.updateuser');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Additional route
Route::get('/helloworl', [ProductController::class, 'helloWorl'])->name('helloworl');
