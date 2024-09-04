<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;

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
    Route::post('upload', [BlogController::class, 'upload'])->name('upload');
    Route::delete('revert', [BlogController::class, 'revert'])->name('revert');







    
});

require __DIR__.'/auth.php';

//For blog post in simple all not using resource controller using simple controler ans route



//for category route using resource controller
