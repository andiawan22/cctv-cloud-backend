@extends('layouts.user')

@section('title', 'Live View - ' . $camera->camera_name)

@section('content')

@php
    // Menghapus $zmHost dan menggunakan Relative Path
    $streamUrl = '/zm/cgi-bin/nph-zms?mode=jpeg&monitor=' . $camera->zoneminder_monitor_id;
    if (!empty($zmToken)) { $streamUrl .= '&token=' . $zmToken; } 
    else { $streamUrl .= '&user=' . $zmUser . '&pass=' . $zmPass; }
@endphp

<!-- HEADER HALAMAN -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3">
            <a href="{{ route('user.cameras.index') }}" class="text-slate-400 hover:text-blue-600 transition" title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                </svg>
            </a>
            {{ $camera->camera_name }}
        </h1>
        <p class="text-slate-500 mt-1 ml-11">
            <span class="bg-slate-200 text-slate-700 px-2 py-1 rounded text-xs font-semibold mr-2">{{ $camera->connection_type }}</span> 
            IP: {{ $camera->ip_address ?? 'Otomatis' }}
        </p>
    </div>
    
    <div class="flex gap-3">
        <a href="{{ route('user.live.grid') }}" class="bg-slate-700 hover:bg-slate-800 text-white px-5 py-2.5 rounded-lg shadow-sm font-medium transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Lihat Semua (Grid)
        </a>
    </div>
</div>

<!-- KANVAS VIDEO (PLAYER UTAMA) -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 max-w-5xl mx-auto mb-8">
    <div class="bg-slate-900 rounded-xl overflow-hidden relative group flex items-center justify-center" style="width: 100%; aspect-ratio: 16/9; min-height: 400px;">
        
        @if($camera->status == 'online')
            <!-- Streaming Video -->
            <img src="{{ $streamUrl }}" 
                 alt="Stream {{ $camera->camera_name }}"
                 class="w-full h-full object-contain"
                 onerror="this.onerror=null; this.parentElement.classList.add('text-white'); this.style.display='none'; document.getElementById('error-ui').style.display='flex';">
            
            <!-- Indikator LIVE (Diperbaiki Posisinya) -->
            <div class="absolute top-4 right-4 z-10 pointer-events-none">
                <span class="bg-black/50 text-white px-3 py-1.5 rounded-full text-sm backdrop-blur-md flex items-center gap-2 border border-white/10 shadow-lg">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span> 
                    <span class="font-semibold tracking-wide">LIVE</span>
                </span>
            </div>

            <!-- UI Error (Sembunyi secara default) -->
            <div id="error-ui" style="display: none;" class="flex-col items-center justify-center w-full h-full absolute inset-0 bg-slate-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                <p class="text-slate-300 text-lg font-medium">Koneksi Video Terputus</p>
                <p class="text-slate-500 text-sm mt-1">Sistem sedang mencoba menghubungkan ulang ke ZoneMinder...</p>
            </div>
        @else
            <!-- UI Offline -->
            <div class="flex flex-col items-center justify-center w-full h-full text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span class="text-2xl font-medium">Kamera Sedang Offline</span>
                <span class="mt-2 text-sm">Pastikan status kamera pada menu "Kamera Saya" berwarna hijau.</span>
            </div>
        @endif
        
    </div>
</div>

@endsection