<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controller Admin
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\CameraController;
use App\Http\Controllers\Admin\StorageController;

/*
|--------------------------------------------------------------------------
| Controller User
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\CameraController as UserCameraController;

/*
|--------------------------------------------------------------------------
| Controller Profile
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {
    if (auth()->user()->role == 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('packages', PackageController::class);
        
        // --- ROUTE SINKRONISASI STATUS ADMIN ---
        Route::post('/cameras/sync-status', [CameraController::class, 'syncStatus'])->name('cameras.sync_status');
        
        // --- ROUTE LIVE VIEW ADMIN ---
        Route::get('/live', [CameraController::class, 'liveGrid'])->name('live.grid');
        Route::get('/live/{camera}', [CameraController::class, 'liveSingle'])->name('live.single');
                
        Route::resource('cameras', CameraController::class);
        
        // --- ROUTE STORAGE SERVER ---
        Route::get('/storage', [StorageController::class, 'index'])->name('storage.index');
        
        // [BARU] Route untuk Generate Folder sesuai Flowchart
        Route::post('/storage/generate', [StorageController::class, 'generateFolders'])->name('storage.generate');
            
});

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // --- ROUTE SINKRONISASI STATUS USER ---
        Route::post('/cameras/sync-status', [UserCameraController::class, 'syncStatus'])->name('cameras.sync_status');

        // --- ROUTE LIVE VIEW USER ---
        Route::get('/live', [UserCameraController::class, 'liveGrid'])->name('live.grid');
        Route::get('/live/{camera}', [UserCameraController::class, 'liveSingle'])->name('live.single');
        // --- ROUTE PLAYBACK USER ---
        Route::get('/playback', [UserCameraController::class, 'playbackIndex'])->name('playback.index');
        Route::get('/playback/show', [UserCameraController::class, 'playbackShow'])->name('playback.show');
        Route::get('/playback/play/{camera_id}/{tanggal}/{event_id}', [UserCameraController::class, 'playbackPlay'])->name('playback.play');
        Route::get('/playback/stream', [UserCameraController::class, 'streamVideo'])->name('playback.stream');

        Route::resource('cameras', UserCameraController::class);
      
});

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';