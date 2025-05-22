<?php

// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\ContactController;

Route::get('/ping', fn () => 'pong');


Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{post:slug}', [PostController::class, 'show']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/tags', [TagController::class, 'index']);

    Route::get('/ads', [AdController::class, 'index']);
    Route::get('/ads/positions', [AdController::class, 'positions']);

    Route::post('/contacts', [ContactController::class, 'store']);
});
