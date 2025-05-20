<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Route::get('/api/ping', fn () => 'pong');
// Route::get('/api/posts', [PostController::class, 'index']);

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
