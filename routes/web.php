<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EdwardController;

Route::get('/', function () {
    return view('app');
});

// Dynamic Sitemap for Google and Search Engines
Route::get('/sitemap.xml', [BlogController::class, 'sitemap'])->name('sitemap');

// Blog Routes (Edward AI Content)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Edward AI Direct Webhook and Verification Routes
Route::match(['get', 'post'], '/webhooks/edward', [EdwardController::class, 'publish'])->name('edward.publish');
Route::match(['get', 'post'], '/api/edward-webhook', [EdwardController::class, 'publish']);
Route::match(['get', 'post'], '/edward-webhook', [EdwardController::class, 'publish']);
Route::get('/edward/verify', [EdwardController::class, 'ping'])->name('edward.verify');
Route::get('/edward/diagnostics', [EdwardController::class, 'diagnostics'])->name('edward.diagnostics');

// Edward WordPress REST API Compatibility Routes (for WP-based connectors)
Route::prefix('wp-json/edward/v1')->group(function () {
    Route::get('/ping', [EdwardController::class, 'ping']);
    Route::get('/diagnostics', [EdwardController::class, 'diagnostics']);
    Route::get('/taxonomies', [EdwardController::class, 'taxonomies']);
    Route::get('/authors', [EdwardController::class, 'authors']);
    Route::match(['get', 'post'], '/posts', [EdwardController::class, 'publish']);
});

Route::prefix('wp-json/wp/v2')->group(function () {
    Route::match(['get', 'post'], '/posts', [EdwardController::class, 'publish']);
    Route::get('/categories', [EdwardController::class, 'taxonomies']);
    Route::get('/users', [EdwardController::class, 'authors']);
});

// Peach Payments Hosted Checkout & Webhook Routes
Route::post('/payments/checkout', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payments.checkout');
Route::get('/payments/result', [\App\Http\Controllers\PaymentController::class, 'result'])->name('payments.result');
Route::get('/payments/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payments.cancel');
Route::post('/webhooks/peach', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payments.peach.webhook');

// Legacy PayFast Routes for backward compatibility
Route::get('/payment/return', [\App\Http\Controllers\PaymentController::class, 'return'])->name('payment.return');
Route::get('/payment/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/payment/notify', [\App\Http\Controllers\PaymentController::class, 'notify'])->name('payment.notify');

