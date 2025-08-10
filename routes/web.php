<?php

use App\Http\Controllers\TusUploadController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Livewire\LhpDetail;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard-v2')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth','verified'])->group(function () {
    Route::get('lhp', \App\Livewire\LhpManager::class)->name('lhps');
    Route::get('lhp/{id}', LhpDetail::class)->name('lhp.detail');
    Route::get('irban', \App\Livewire\IrbanManager::class)->name('irbans');
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// TUS Upload Endpoint
// 1. Route definition (web.php or api.php)
// Route::post('/tus-upload', [UploadController::class, 'handleResumableUpload'])->name('tus-upload');
// Route::get('/tus-upload', [UploadController::class, 'testResumableUpload']); // For chunk testing
// Route::any('/tus-upload/{any?}', [TusUploadController::class, 'handleUpload'])
//     ->where('any', '.*')
//     ->middleware(['auth', 'irban'])->name('tus-upload');

   // Handle chunk uploads
   Route::post('/resumable-upload', [UploadController::class, 'upload'])
   ->name('resumable.upload');

// Check if chunk exists (for resume capability)
Route::get('/resumable-upload', [UploadController::class, 'check'])
   ->name('resumable.check');
require __DIR__.'/auth.php';
