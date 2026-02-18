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

Route::get('/campaigns', [CampaignController::class, 'index']);
Route::post('/campaigns', [CampaignController::class, 'store']);

Route::get('/chat', [ChatController::class, 'index']);
Route::post('/chat', [ChatController::class, 'store']);

Route::get('/audit', [AuditController::class, 'index']);
