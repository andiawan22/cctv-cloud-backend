@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<!-- Header -->
<div class="flex justify-between items-center mb-8">

    <div>
        <h1 class="text-3xl font-bold">
            Selamat Datang, {{ Auth::user()->name }} 👋
        </h1>

        <p class="text-gray-500 mt-2">
            Berikut ringkasan sistem Cloud CCTV.
        </p>
    </div>

    <div class="text-right">
        <p class="text-gray-500">
            {{ now()->format('d F Y') }}
        </p>

        <p class="text-2xl font-semibold">
            {{ now()->format('H:i') }}
        </p>
    </div>

</div>


<!-- Card Statistik -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

    <!-- Total User -->
    <div class="dashboard-card flex justify-between items-center">

        <div>

            <p class="text-gray-500">
                Total Pelanggan
            </p>

            <h2 class="text-4xl font-bold mt-2">
                {{ $totalUsers }}
            </h2>

            <span class="text-green-600">
                Aktif : {{ $activeUsers }}
            </span>

        </div>

        <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center text-3xl">

            👥

        </div>

    </div>


    <!-- Paket -->
    <div class="dashboard-card flex justify-between items-center">

        <div>

            <p class="text-gray-500">
                Paket Layanan
            </p>

            <h2 class="text-4xl font-bold mt-2">
                {{ $totalPackages }}
            </h2>

            <span class="text-blue-600">
                Paket tersedia
            </span>

        </div>

        <div class="w-16 h-16 rounded-xl bg-yellow-100 flex items-center justify-center text-3xl">

            📦

        </div>

    </div>


    <!-- Kamera -->
    <div class="dashboard-card flex justify-between items-center">

        <div>

            <p class="text-gray-500">
                Total Kamera
            </p>

            <h2 class="text-4xl font-bold mt-2">
                {{ $totalCameras }}
            </h2>

            <span class="text-green-600">
                Kamera terdaftar
            </span>

        </div>

        <div class="w-16 h-16 rounded-xl bg-green-100 flex items-center justify-center text-3xl">

            📷

        </div>

    </div>


    <!-- Storage -->
    <div class="dashboard-card flex justify-between items-center">

        <div>

            <p class="text-gray-500">
                Storage Dialokasikan
            </p>

            <h2 class="text-4xl font-bold mt-2">
                {{ $storageUsed }} GB
            </h2>

            <span class="text-indigo-600">
                Untuk seluruh user
            </span>

        </div>

        <div class="w-16 h-16 rounded-xl bg-purple-100 flex items-center justify-center text-3xl">

            💾

        </div>

    </div>


    <!-- User Aktif -->
    <div class="dashboard-card flex justify-between items-center">

        <div>

            <p class="text-gray-500">
                User Aktif
            </p>

            <h2 class="text-4xl font-bold mt-2">
                {{ $activeUsers }}
            </h2>

            <span class="text-green-600">
                Sedang aktif
            </span>

        </div>

        <div class="w-16 h-16 rounded-xl bg-emerald-100 flex items-center justify-center text-3xl">

            🟢

        </div>

    </div>


    <!-- User Nonaktif -->
    <div class="dashboard-card flex justify-between items-center">

        <div>

            <p class="text-gray-500">
                User Nonaktif
            </p>

            <h2 class="text-4xl font-bold mt-2">
                {{ $inactiveUsers }}
            </h2>

            <span class="text-red-600">
                Tidak aktif
            </span>

        </div>

        <div class="w-16 h-16 rounded-xl bg-red-100 flex items-center justify-center text-3xl">

            🔴

        </div>

    </div>

</div>

@endsection