<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|
|
*/

Route::get('/books', [BookController::class, 'index']);

Route::post('/loans', [LoanController::class, 'store']);

Route::post('/loans/{id}/return', [LoanController::class, 'return']);
