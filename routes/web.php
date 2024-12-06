<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Kernel;
use App\Http\Controllers\ScoresController;
use App\Http\Controllers\LeaderboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/players', [PlayerController::class, 'index'])->name('players.index');

Route::get('/players/create', [PlayerController::class, 'create'])->name('players.create');

Route::post('/players', [PlayerController::class, 'store'])->name('players.store');

Route::delete('/players{id}', [PlayerController::class, 'delete'])->name('players.destroy');

Route::get('/players/{id}', [PlayerController::class, 'show'])->name('players.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::post('/friends/add', [FriendController::class, 'add'])->name('friends.add');

Route::get('/friends/requests', [FriendController::class, 'requests'])->name('friends.requests');

Route::post('/friends/accept/{id}', [FriendController::class, 'accept'])->name('friends.accept');

Route::post('/friends/search', [FriendController::class, 'search'])->name('friends.search');

Route::get('/api/friends', [DashboardController::class, 'getFriends'])->name('api.friends');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::patch('/admin/users/{id}/role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
    Route::delete('/admin/posts/{id}', [AdminController::class, 'deletePost'])->name('admin.deletePost');
});

Route::middleware(['role:moderator'])->group(function () {
    Route::get('/moderator/panel', [ModeratorController::class, 'index'])->name('moderator.panel');
});

// Route for viewing all posts
Route::get('/posts', [PostsController::class, 'index'])->name('posts.index');

// Route for creating a new post (no postId needed here)
Route::get('/posts/create', [PostsController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostsController::class, 'store'])->name('posts.store');

// Route for adding a comment to an existing post (postId required)
Route::post('/posts/{post}/comments', [PostsController::class, 'storeComment'])
    ->middleware('auth')
    ->name('posts.comments.store');


Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');

Route::get('/my-posts', [PostsController::class, 'myPosts'])->name('posts.myPosts');

Route::get('/posts/{post}/edit', [PostsController::class, 'edit'])->name('posts.edit')->middleware('auth');

Route::put('/posts/{post}', [PostsController::class, 'update'])->name('posts.update')->middleware('auth');

Route::delete('/posts/{post}', [PostsController::class, 'destroy'])->name('posts.destroy')->middleware('auth');

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

Route::middleware(['auth'])->group(function () {
    // Round listing (if needed)
    Route::get('/scores', [ScoresController::class, 'index'])->name('scores.index');

    // Show form for creating a new round
    Route::get('/scores/create', [ScoresController::class, 'create'])->name('scores.create');

    // Store a newly created round
    Route::post('/scores', [ScoresController::class, 'store'])->name('scores.store');

    // Show a specific round
    Route::get('/scores/{score}', [ScoresController::class, 'show'])->name('scores.show');

    // Edit a round
    Route::get('/scores/{score}/edit', [ScoresController::class, 'edit'])->name('scores.edit');

    // Update a round
    Route::put('/scores/{score}', [ScoresController::class, 'update'])->name('scores.update');

    // Delete a round
    Route::delete('/scores/{score}', [ScoresController::class, 'destroy'])->name('scores.destroy');

});

require __DIR__.'/auth.php';
