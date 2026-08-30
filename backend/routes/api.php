<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GetCategoryAuctionController;
use App\Http\Controllers\GetUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    Route::get('/categories', GetCategoryAuctionController::class)->middleware(['auth:sanctum', 'role:petugas']);
    Route::get('/users', GetUserController::class)->middleware(['auth:sanctum', 'role:petugas']);

    Route::get('/dashboard', DashboardController::class)->middleware(['auth:sanctum']);

    Route::group(['prefix' => 'assets'], function () {
        Route::get('/', [HomeController::class, 'index']);
        Route::get('/{id}', [HomeController::class, 'show']);
    });

    Route::middleware(['auth:sanctum', 'role:administrator,petugas'])->prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::get('/print/{id}', [ReportController::class, 'download_detail_pdf']);
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
    });

    // Grouping middlware and prefix
    Route::middleware(['auth:sanctum', 'role:administrator,petugas'])->prefix('auctions')->group(function () {
        Route::get('/live', [AuctionController::class, 'index']);
        Route::get('/live/{id_lelang}', [AuctionController::class, 'show']);

        Route::get('/', [ItemController::class, 'index']);
        Route::post('/', [ItemController::class, 'store']);
        Route::put('/{id}', [ItemController::class, 'update']);
        Route::get('/{id}', [ItemController::class, 'show']);
        Route::delete('/{id}', [ItemController::class, 'destroy']);
        Route::post('/{id}/join-bid', [ItemController::class, 'join_bid'])->withoutMiddleware(['role:administrator,petugas']);
    });

    Route::middleware(['auth:sanctum', 'role:administrator'])->prefix('officers')->group(function () {
        Route::get('/', [OfficerController::class, 'index']);
        Route::post('/', [OfficerController::class, 'store']);
        Route::put('/{id}', [OfficerController::class, 'update']);
        Route::get('/{id}', [OfficerController::class, 'show']);
        Route::delete('/{id}', [OfficerController::class, 'destroy']);
    });
});
