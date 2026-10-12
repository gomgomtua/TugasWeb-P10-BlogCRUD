<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Tugas Rutin 10 (Blog CRUD)
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/posts');

// Route tambahan (soft delete). WAJIB didaftarkan SEBELUM Route::resource,
// supaya "/posts/trash" tidak dianggap sebagai {post} oleh route show.
Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('trash', [PostController::class, 'trash'])->name('trash');

    // withTrashed(): izinkan route model binding menemukan data yang sudah di-soft-delete.
    Route::patch('{post}/restore', [PostController::class, 'restore'])
        ->withTrashed()
        ->name('restore');

    Route::delete('{post}/force', [PostController::class, 'forceDestroy'])
        ->withTrashed()
        ->name('force-destroy');
});

// 7 route CRUD dalam 1 baris: posts.index/create/store/show/edit/update/destroy
Route::resource('posts', PostController::class);
