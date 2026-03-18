<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\XenditWebhookController;

Route::post('/xendit/callback', [XenditWebhookController::class, 'handle']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
