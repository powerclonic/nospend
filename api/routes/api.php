<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/expenses', ExpenseController::class)
    ->middleware('auth:sanctum');

require __DIR__ . '/auth.php';
