<?php

use Illuminate\Support\Facades\Route;

// Controllers;
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

// Route::get('/', function () {
//     return redirect('/posts');
// });

// Route::get('/posts', function () {
//     return view('posts.index');
// })->name('home');

// Route::get('/posts/create', [PostController::class, 'showNewPostForm'])->name('new-post');

// Route::post('/posts/create', [PostController::class, 'storeNewPost'])->name('store-post');

// Route::get('/posts/x', function () {
//     return view('posts.show');
// });

// Route::get('/posts/x/edit', function () {
//     return view('posts.edit');
// });

Route::resource('posts', PostController::class);
Route::redirect('/', route('posts.index'));

// -----------------------------------------

// Route::get('/categories/create', [CategoryController::class, 'showNewCategoryForm'])->name('new-category');

// Route::post('/categories/create', [CategoryController::class, 'storeNewCategory'])->name('store-category');

// Route::get('/categories/x', function () {
//     return view('categories.show');
// });

Route::resource('categories', CategoryController::class)->only(['create', 'store', 'edit', 'update']);

// -----------------------------------------

Auth::routes();
