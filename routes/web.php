<?php

use App\Http\Controllers\TusUploadController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Livewire\LhpDetail;
use App\Http\Controllers\LhpExportController;
use App\Livewire\Report\LhpReport;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::redirect('/', '/login');
Route::get('dashboard', \App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('lhp', \App\Livewire\LhpManager::class)->name('lhps');
    Route::get('lhp/{id}', LhpDetail::class)->name('lhp.detail');
    Route::get('lhps/{lhp}/export-pdf', [LhpExportController::class, 'exportPdf'])->name('lhp.export-pdf');
    Route::get('reports/lhp', LhpReport::class)->name('reports.lhp');
    Route::get('reports/lhp/export', [LhpExportController::class, 'exportPdf'])->name('reports.lhp.export');
    Route::get('irban', \App\Livewire\IrbanManager::class)->name('irbans');
    Route::get('pegawai', \App\Livewire\PegawaiManager::class)->name('pegawai');
    Route::get('arsip', \App\Livewire\ArsipManager::class)->name('arsip');
    Route::get('jabatan', \App\Livewire\JabatanManager::class)->name('jabatan');
    Route::get('jabatan-manager', \App\Livewire\JabatanManager::class)->name('jabatan-manager');
    Route::get('jabatan-tim-manager', \App\Livewire\JabatanTimManager::class)->name('jabatan-tim-manager');
    Route::get('tindak-lanjut', \App\Livewire\TindakLanjutManager::class)->name('tindak-lanjut');
    Route::get('tindak-lanjuts', \App\Livewire\TindakLanjutManager::class)->name('tindak-lanjuts');
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// Custom file upload endpoint for LHP documents - POST only
Route::post('/lhp/upload-file', [UploadController::class, 'livewireUpload'])
    ->middleware(['auth', 'web'])
    ->name('lhp.upload-file');

require __DIR__ . '/auth.php';
