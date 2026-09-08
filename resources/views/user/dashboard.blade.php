@extends('layouts.user')

@section('title','Dashboard User')

@section('content')

<div class="mb-8">

    <h1 class="text-3xl font-bold">
        Halo, {{ $user->name }} 👋
    </h1>

    <p class="text-gray-500">
        Selamat datang di Cloud CCTV
    </p>

</div>

<div class="grid grid-cols-4 gap-6">

    {{-- Storage --}}
    <div class="bg-white rounded-xl shadow p-6">

        <h3 class="text-gray-500">
            Storage Tersisa
        </h3>

        <h1 class="text-3xl font-bold mt-2 text-blue-600">

            {{ number_format($storage->remaining_storage_gb,2) }} GB

        </h1>

        <p class="text-sm text-gray-500 mt-2">

            Terpakai :
            {{ number_format($storage->used_storage_gb,2) }} GB

        </p>

    </div>

    {{-- Kamera --}}
    <div class="bg-white rounded-xl shadow p-6">

        <h3 class="text-gray-500">
            Kamera
        </h3>

        <h1 class="text-3xl font-bold mt-2">

            {{ $totalCamera }}

        </h1>

    </div>

    {{-- Online --}}
    <div class="bg-white rounded-xl shadow p-6">

        <h3 class="text-gray-500">
            Kamera Online
        </h3>

        <h1 class="text-3xl font-bold mt-2 text-green-600">

            {{ $onlineCamera }}

        </h1>

    </div>

    {{-- Paket --}}
    <div class="bg-white rounded-xl shadow p-6">

        <h3 class="text-gray-500">
            Paket
        </h3>

        <h1 class="text-2xl font-bold mt-2">

            {{ $user->package->name }}

        </h1>

    </div>

</div>

@php

$quota = $user->package->storage_limit_gb;

$used = $storage->used_storage_gb;

$percent = $quota > 0 ? ($used / $quota) * 100 : 0;

@endphp

<div class="bg-white rounded-xl shadow p-6 mt-8">

    <h2 class="text-xl font-bold mb-5">

        Penggunaan Storage

    </h2>

    <div class="flex justify-between mb-2">

        <span>Terpakai</span>

        <span>

            {{ number_format($used,2) }}
            /
            {{ $quota }} GB

        </span>

    </div>

    <div class="w-full bg-gray-200 rounded-full h-5">

        <div
            class="bg-blue-600 h-5 rounded-full"
            style="width: {{ $percent }}%;">

        </div>

    </div>

    <div class="mt-3 text-center text-gray-600">

        {{ number_format($percent,1) }} %

    </div>

</div>

@endsection