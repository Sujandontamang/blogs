<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AlbumimagesController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//dash board route

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

//authenticate and middleware for profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //blog category controler route
    Route::resource('category', BlogCategoryController::class);

    Route::resource('blog', BlogController::class);
    Route::resource('user', userController::class);
    Route::post('upload', [BlogController::class, 'upload'])->name('upload');
    Route::delete('revert', [BlogController::class, 'revert'])->name('revert');
    Route::post('/blog/new', [BlogController::class, 'updateStatus'])->name('blog.status');
    Route::post('/blog/status/update', [BlogCategoryController::class, 'updateStatus'])->name('blogcategory.status');
    Route::post('/user/status/update', [userController::class, 'updateStatus'])->name('user.status');
    Route::get('/user/password/{id}', [userController::class, 'password'])->name('password');
    Route::put('/user/password/change/{id}', [userController::class, 'updatepassword'])->name('user.password.change');
    Route::post('/blog-category/bulk-update-status', [BlogCategoryController::class, 'bulkUpdateStatus'])->name('blog-category.bulk-update-status');
    Route::post('/blog-category/bulk-delete', [BlogCategoryController::class, 'bulkDelete'])->name('blog-category.bulk-delete');
    Route::post('/blog/bulk-update-status', [BlogController::class, 'bulkUpdateStatus'])->name('blog.bulk-update-status');
    Route::post('/blog/bulk-delete', [BlogController::class, 'bulkDelete'])->name('blog.bulk-delete');
    Route::post('/user/bulk-update-status', [userController::class, 'bulkUpdateStatus'])->name('user.bulk-update-status');
    Route::post('/user/bulk-delete', [userController::class, 'bulkDelete'])->name('user.bulk-delete');
    Route::resource('/Album', AlbumController::class);

    Route::post('/multipleupload', [AlbumController::class, 'multipleUpload'])->name('multipleUpload');
    Route::resource('/Image', AlbumimagesController::class);
    Route::get('/album/{album}/images', [AlbumimagesController::class, 'albumImage'])->name('album.image');
    Route::get('/album/{album}/images/cover', [AlbumimagesController::class, 'coverImage'])->name('image.cover');

});

require __DIR__.'/auth.php';

//For blog post in simple all not using resource controller using simple controler ans route

//for category route using resource controller
