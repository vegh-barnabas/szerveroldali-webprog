<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::resource('categories', CategoryController::class);

Route::patch('/posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
Route::resource('posts', PostController::class);

Route::redirect('/', route('posts.index'));
