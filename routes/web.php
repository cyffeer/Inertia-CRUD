<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('posts', PostController::class);
    //posts
    Route::get('/api/posts', [PostController::class, 'index']);
    //edit
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    //delete
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    //index
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    //view all
    Route::get('/posts/show', [PostController::class, 'show'])->name('posts.show');
    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');




    
});

require __DIR__.'/auth.php';