<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\PostPageController;
use App\Http\Controllers\Frontend\AuthPageController;

Route::get('/login', [AuthPageController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthPageController::class, 'showRegister'])->name('register');
Route::post('/logout', [AuthPageController::class, 'logout'])->name('logout');

Route::post('/save-token', [AuthPageController::class, 'saveToken'])->name('save.token');


Route::get('/', [PostPageController::class, 'index'])->name('posts.list'); 
Route::get('/posts/{id}', [PostPageController::class, 'show'])->name('posts.show');

Route::get('/dashboard', [PostPageController::class, 'dashboard'])->name('posts.dashboard')->middleware('jwt.session');

