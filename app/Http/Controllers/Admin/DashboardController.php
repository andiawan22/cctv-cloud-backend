<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Camera;
use App\Models\Package;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();

        $activeUsers = User::where('role', 'user')
            ->where('status', 'active')
            ->count();

        $inactiveUsers = User::where('role', 'user')
            ->where('status', 'inactive')
            ->count();

        $totalPackages = Package::count();

        $totalCameras = Camera::count();

        // Total storage seluruh user berdasarkan paket
        $storageUsed = User::with('package')
            ->where('role', 'user')
            ->get()
            ->sum(function ($user) {
                return $user->package->storage_limit_gb ?? 0;
            });

        return view('dashboard', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'totalPackages',
            'totalCameras',
            'storageUsed'
        ));
    }
}