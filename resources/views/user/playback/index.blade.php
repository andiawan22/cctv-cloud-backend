@extends('layouts.user')

@section('title', 'Playback CCTV')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-slate-800">Playback Rekaman</h1>
    <p class="text-slate-500 mt-1">Pilih kamera dan tanggal untuk melihat histori rekaman Anda.</p>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <!-- Nanti form ini akan diarahkan ke halaman pencarian video -->
    <form action="{{ route('user.playback.show') }}" method="GET" class="space-y-6">
        
        <!-- Pilihan Kamera -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Kamera</label>
            <select name="camera_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <option value="" disabled selected>-- Pilih Kamera --</option>
                @foreach ($cameras as $kamera)
                    <option value="{{ $kamera->id }}">{{ $kamera->camera_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Pilihan Tanggal -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Tanggal</label>
            <input type="date" name="tanggal" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <!-- Tombol Cari -->
        <div class="pt-2">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg shadow transition">
                Cari Rekaman
            </button>
        </div>
    </form>
</div>
@endsection