<?php

use Illuminate\Support\Facades\Route;

// Controllers;
use App\Http\Controllers\CategoryController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', function () {
    return view('posts.index');
})->name('home');

Route::get('/posts/create', function () {
    return view('posts.create');
});

Route::get('/posts/x', function () {
    return view('posts.show');
});

Route::get('/posts/x/edit', function () {
    return view('posts.edit');
});

// -----------------------------------------

Route::get('/categories/create', [CategoryController::class, 'showNewCategoryForm'])->name('new-category');

Route::post('/categories/create', [CategoryController::class, 'storeNewCategory'])->name('store-category');

// -----------------------------------------

Route::get('/categories/x', function () {
    return view('categories.show');
});

// -----------------------------------------

Auth::routes();
