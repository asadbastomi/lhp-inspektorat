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
    Route::get('tindak-lanjut', \App\Livewire\TindakLanjutManager::class)->name('tindak-lanjut');
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// Livewire file upload endpoint - POST only
Route::post('/livewire/upload-file', [UploadController::class, 'livewireUpload'])
    ->middleware(['auth', 'web'])
    ->name('livewire.upload-file');

// Add a GET route that returns a 405 Method Not Allowed response
Route::get('/livewire/upload-file', function() {
    return response()->json([
        'success' => false,
        'message' => 'Method not allowed. Use POST for file uploads.'
    ], 405);
})->middleware('auth');
require __DIR__.'/auth.php';
