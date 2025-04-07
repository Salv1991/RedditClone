<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return Inertia::render('Home', [
    ]);
})->name('home');

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::get('/popular', [PostController::class, 'popular'])->name('post.popular');

Route::get('/user-posts', [PostController::class, 'userPosts'])->name('post.user-posts');

Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');

Route::post('/post/{id}/upvote', [PostController::class, 'upvote'])->name('post.upvote');
Route::post('/post/{id}/downvote', [PostController::class, 'downvote'])->name('post.downvote');

Route::middleware('auth')->group(function() {

    Route::get('/users', function () {
        return Inertia::render('Users/Index', [
            'users' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like',  '%' . $search . '%');
                })
                ->paginate(10)
                ->withQueryString()
                ->through(function($user) {
                return [
                    'name' => $user->name,
                    'id' => $user->id
                ];
            }),

            'filters' => Request::only('search'),
        ]);
    });

    Route::get('/users/create', function() {
        return Inertia::render('Users/Create');
    });

    Route::post('/users', function() {

        $attributes = Request::validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => [ 'required']
        ]);

        $user = User::create($attributes);
        return redirect('/users');
    });

    Route::get('/settings', function () {
        return Inertia::render('Settings', [
            'name' => 'Poutsa'
        ]);
    });

    Route::post('/logout', function () {
        dd('Logout');
    });
});
