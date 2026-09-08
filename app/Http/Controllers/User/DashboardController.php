<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | Kamera
    |--------------------------------------------------------------------------
    */

    // Jumlah kamera yang dimiliki user
    $totalCamera = $user->cameras()->count();

    // Kamera online
    $onlineCamera = $user->cameras()
        ->where('status', 'online')
        ->count();

    // Maksimal kamera berdasarkan paket
    $cameraLimit = $user->package->camera_limit;

    // Sisa kamera
    $remainingCamera = $cameraLimit - $totalCamera;

    // Persentase penggunaan kamera
    $cameraPercentage = 0;

    if ($cameraLimit > 0) {

        $cameraPercentage = ($totalCamera / $cameraLimit) * 100;

    }


    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    */

    $storage = $user->storageUsage;

    // Total storage paket
    $storageQuota = $user->package->storage_limit_gb;

    // Storage terpakai
    $usedStorage = $storage->used_storage ?? 0;

    // Storage tersisa
    $remainingStorage = $storage->remaining_storage ?? $storageQuota;

    // Persentase penggunaan storage
    $storagePercentage = 0;

    if ($storageQuota > 0) {

        $storagePercentage = ($usedStorage / $storageQuota) * 100;

    }


    return view('user.dashboard', compact(

        'user',

        'storage',

        'totalCamera',

        'onlineCamera',

        'cameraLimit',

        'remainingCamera',

        'cameraPercentage',

        'storageQuota',

        'usedStorage',

        'remainingStorage',

        'storagePercentage'

    ));
}
}