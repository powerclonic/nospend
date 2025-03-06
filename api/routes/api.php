<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseDetailsController;

Route::middleware(['auth:sanctum'])->get('/auth-check', function () {
    return response('', 204);
});

Route::get('/expenses/details', ExpenseDetailsController::class)
    ->middleware('auth:sanctum');
    
Route::apiResource('/expenses', ExpenseController::class)
    ->middleware('auth:sanctum');
