<?php

use App\Http\Controllers\DeliveryPointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Simple test route to see if API routes work
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::apiResource('delivery', DeliveryPointController::class)->only(['index', 'store', 'show']); 