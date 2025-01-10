<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostSearchController;
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
    
    //auth user
    Route::get('/api/auth-user', [PostController::class, 'getAuthenticatedUser']);

    //posts
    Route::get('/api/posts', [PostController::class, 'index']);
    Route::resource('posts', PostController::class);

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
    
    //search
    //Route::get('/search', [PostSearchController::class, 'search'])->name('search');
    //Route::get('/posts/{id}', [PostSearchController::class, 'show'])->name('posts.show');

    //fetch
    Route::get('/api/posts', [PostController::class, 'fetchPosts']);



    
});

require __DIR__.'/auth.php';