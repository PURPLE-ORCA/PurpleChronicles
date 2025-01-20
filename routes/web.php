<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PostController::class, 'index']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public routes for blog viewing
Route::group(['as' => 'blog.'], function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/post/{post:slug}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/add-post', [PostController::class, 'store'])->name('posts.store');
    Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);
    Route::get('/tags/{tag:slug}', [TagController::class, 'show'])->name('tags.show');
});

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
});

Route::get('/dashboard', [CommentController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Author routes
Route::middleware(['auth', 'can:author'])->group(function () {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});

// Admin routes
Route::middleware(['auth', 'can:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('tags', TagController::class)->except(['show']);
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::patch('/admin/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::patch('/users/{user}/role', [DashboardController::class, 'updateUserRole'])->name('users.updateRole');
    Route::post('/users', [DashboardController::class, 'createUser'])->name('users.create');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';