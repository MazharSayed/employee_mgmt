<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EmploymentHistoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [EmployeeController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::post('/profile/update', [EmployeeController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/upload-picture', [EmployeeController::class, 'uploadPicture'])->name('profile.upload-picture');

    // Employment history routes
    Route::get('/employment-history', [EmploymentHistoryController::class, 'index'])->name('employment.index');
    Route::post('/employment-history/add', [EmploymentHistoryController::class, 'store'])->name('employment.store');
    Route::post('/employment-history/update', [EmploymentHistoryController::class, 'update'])->name('employment.update');
    Route::delete('/employment-history/delete/{id}', [EmploymentHistoryController::class, 'destroy'])->name('employment.destroy');
    Route::get('/employment-history/details/{id}', [EmploymentHistoryController::class, 'details'])->name('employment.details');

});

require __DIR__.'/auth.php';
