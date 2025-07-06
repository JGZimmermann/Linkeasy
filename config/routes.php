<?php

use App\Controllers\AuthenticationsController;
use App\Controllers\ProfileController;
use App\Controllers\PostController;
use App\Controllers\TagController;
use Core\Router\Route;

// Authentication
Route::get('/login', [AuthenticationsController::class, 'new'])->name('users.login');
Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('users.authenticate');

Route::middleware('auth')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('root');

    // Create
    Route::get('/posts/new', [PostController::class, 'new'])->name('posts.new');
    Route::post('/posts', [PostController::class, 'create'])->name('posts.create');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // Retrieve
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/page/{page}', [PostController::class, 'index'])->name('posts.paginate');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::get('/tags/page/{page}', [TagController::class, 'index'])->name('tags.paginate');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');
    Route::get('/posts/filter/{id}', [TagController::class, 'postsFilteredByTags'])->name('posts.filter');
    Route::post('/posts/search', [PostController::class, 'search'])->name('posts.search');

    // Update
    Route::get('/tags/{id}/edit', [TagController::class, 'edit'])->name('tags.edit');
    Route::put('/tags/{id}', [TagController::class, 'update'])->name('tags.update');

    // Logout
    Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('users.logout');
});

Route::middleware('role')->group(function () {
    //Create
    Route::get('/users/new', [AuthenticationsController::class, 'newUser'])->name('users.new');
    Route::post('/users', [AuthenticationsController::class, 'createUser'])->name('users.create');
    Route::get('/tags/new', [TagController::class, 'new'])->name('tags.new');
    Route::post('/tags', [TagController::class, 'create'])->name('tags.create');

    // Delete
    Route::delete('/tags/{id}', [TagController::class, 'destroy'])->name('tags.destroy');
});

Route::middleware('permission')->group(function () {

    // Update
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');

    // Delete
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

});
