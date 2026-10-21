<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuditController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/members', [MemberController::class, 'index']);
Route::post('/members', [MemberController::class, 'store']);

Route::get('/bookings', [BookingController::class, 'index']);
Route::get('/bookings/member/{memberId}', [BookingController::class, 'byMember']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::put('/bookings/{id}', [BookingController::class, 'update']);
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

Route::get('/campaigns', [CampaignController::class, 'index']);
Route::post('/campaigns', [CampaignController::class, 'store']);

Route::get('/chat', [ChatController::class, 'index']);
Route::post('/chat', [ChatController::class, 'store']);

Route::get('/audit', [AuditController::class, 'index']);

// Reports
Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index']);

// Payment Routes
Route::get('/payment/return', [App\Http\Controllers\PaymentController::class, 'return'])->name('payment.return');
Route::get('/payment/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/payment/notify', [App\Http\Controllers\PaymentController::class, 'notify'])->name('payment.notify');

// Edward AI SEO Integration Endpoints
Route::get('/edward/verify', [\App\Http\Controllers\EdwardController::class, 'ping']);
Route::get('/edward/diagnostics', [\App\Http\Controllers\EdwardController::class, 'diagnostics']);
Route::get('/edward/taxonomies', [\App\Http\Controllers\EdwardController::class, 'taxonomies']);
Route::get('/edward/authors', [\App\Http\Controllers\EdwardController::class, 'authors']);
Route::get('/edward/articles', [\App\Http\Controllers\EdwardController::class, 'articles']);
Route::match(['get', 'post'], '/webhooks/edward', [\App\Http\Controllers\EdwardController::class, 'publish']);
Route::match(['get', 'post'], '/edward-webhook', [\App\Http\Controllers\EdwardController::class, 'publish']);
Route::get('/articles', [\App\Http\Controllers\EdwardController::class, 'articles']);
