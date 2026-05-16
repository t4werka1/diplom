<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdminController;

// Storage route for serving files locally
Route::get('/storage/{path}', [\App\Http\Controllers\StorageController::class, 'serve'])
    ->where('path', '.*')
    ->name('storage.serve');

Route::get('/', [AdController::class, 'index'])->name('home');

// Страница условий использования
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
Route::get('/ads/search', [AdController::class, 'search'])->name('ads.search');
Route::get('/ads/category/{category}', [AdController::class, 'category'])->name('ads.category');

Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show')->middleware('auth');
    Route::get('/my-ads', [AdController::class, 'myAds'])->name('my-ads');
    Route::resource('ads', AdController::class)->except(['index']);
    Route::post('/ads/{ad}/sold', [AdController::class, 'markAsSold'])->name('ads.sold');

    Route::post('/favorites/{ad}', [FavoriteController::class, 'add'])->name('favorites.add');
    Route::delete('/favorites/{ad}', [FavoriteController::class, 'remove'])->name('favorites.remove');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

    Route::get('/profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ads.edit');
Route::put('/ads/{ad}', [AdController::class, 'update'])->name('ads.update');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/ads', [AdminController::class, 'index'])->name('ads.index');
    Route::get('/ads/{ad}', [AdminController::class, 'show'])->name('ads.show');
    Route::post('/ads/{ad}/approve', [AdminController::class, 'approve'])->name('ads.approve');
    Route::post('/ads/{ad}/reject', [AdminController::class, 'reject'])->name('ads.reject');
    Route::delete('/ads/{ad}', [AdminController::class, 'destroy'])->name('ads.destroy');
});
