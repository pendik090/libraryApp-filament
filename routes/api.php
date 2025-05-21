<?php

use App\Filament\Resources\LoansResource\Api\LoansApiService;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\BooksApiController;
use App\Http\Controllers\Api\BooksController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\LoansApiControllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!

*/


Route::post('/login', [ApiAuthController::class, 'auth']);

Route::post('/sign-up', [ApiAuthController::class, 'signUp']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/books', [BooksApiController::class, 'index']);
    Route::get('/books/{books}', [BooksApiController::class, 'show']);

    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::get('/categories/{categories}', [CategoryApiController::class, 'show']);

    Route::get('/loans', [LoansApiControllers::class, 'index']);
    Route::post('/loans', [LoansApiControllers::class, 'store']);

    Route::post('/logout', [ApiAuthController::class, 'logout']);
});
