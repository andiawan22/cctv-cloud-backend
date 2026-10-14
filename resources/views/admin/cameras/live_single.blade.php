@extends('layouts.admin')

@section('title', 'Live View - ' . $camera->camera_name)

@section('content')
@php
    // [PERBAIKAN LOKAL] Merakit URL Streaming menggunakan Absolute Path ($zmHost)
    $streamUrl = '';
    if($camera->status == 'online' && $camera->zoneminder_monitor_id) {
        
        // Tambahkan $zmHost di depan agar menembak IP Proxmox, bukan localhost
        $streamUrl = $zmHost . '/zm/cgi-bin/nph-zms?mode=jpeg&monitor=' . $camera->zoneminder_monitor_id;
        
        if (!empty($zmToken)) { $streamUrl .= '&token=' . $zmToken; } 
        else { $streamUrl .= '&user=' . $zmUser . '&pass=' . $zmPass; }
    }
@endphp

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3">
            <a href="{{ route('admin.live.grid') }}" class="text-slate-400 hover:text-blue-600 transition" title="Kembali">⬅️</a>
            {{ $camera->camera_name }}
        </h1>
        <p class="text-slate-500 mt-1 ml-11">
            Pemilik: <span class="font-semibold text-blue-600">{{ $camera->user->name ?? 'Tidak diketahui' }}</span>
        </p>
    </div>
    
    <a href="{{ route('admin.live.grid') }}" class="bg-slate-700 hover:bg-slate-800 text-white px-5 py-2.5 rounded-lg font-medium transition">
        Lihat Semua (Grid)
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 max-w-5xl mx-auto mb-8">
    <div class="bg-slate-900 rounded-xl overflow-hidden relative flex items-center justify-center" style="width: 100%; aspect-ratio: 16/9; min-height: 400px;">
        @if($camera->status == 'online')
            <img src="{{ $streamUrl }}" alt="Stream {{ $camera->camera_name }}" class="w-full h-full object-contain" onerror="this.onerror=null; this.style.display='none'; document.getElementById('error-ui').style.display='flex';">
            
            <div class="absolute top-4 right-4 z-10 pointer-events-none">
                <span class="bg-black/50 text-white px-3 py-1.5 rounded-full text-sm flex items-center gap-2 border border-white/10">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span> LIVE
                </span>
            </div>

            <div id="error-ui" style="display: none;" class="flex-col items-center justify-center w-full h-full absolute inset-0 bg-slate-900">
                <p class="text-slate-300 text-lg font-medium">Koneksi Video Terputus</p>
            </div>
        @else
            <div class="flex flex-col items-center justify-center w-full h-full text-slate-500">
                <span class="text-2xl font-medium">Kamera Sedang Offline</span>
            </div>
        @endif
    </div>
</div>
@endsection