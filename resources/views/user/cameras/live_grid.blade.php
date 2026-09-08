@extends('layouts.user')

@section('title', 'Live View (Grid)')

@section('content')

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Live View (Grid)
        </h1>
        <p class="text-slate-500 mt-1">
            Pantau semua kamera CCTV Anda secara real-time.
        </p>
    </div>
    <a href="{{ route('user.cameras.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 px-5 py-2.5 rounded-lg shadow-sm font-medium transition flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
        </svg>
        Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($cameras as $camera)
        
        @php
            // Merakit URL Streaming yang bersih
            $streamUrl = '';
            if($camera->status == 'online' && $camera->zoneminder_monitor_id) {
                $streamUrl = $zmHost . '/zm/cgi-bin/nph-zms?mode=jpeg&monitor=' . $camera->zoneminder_monitor_id;
                if (!empty($zmToken)) { $streamUrl .= '&token=' . $zmToken; } 
                else { $streamUrl .= '&user=' . $zmUser . '&pass=' . $zmPass; }
            }
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col group">
            
            <!-- Area Video Utama -->
            <div class="bg-slate-900 relative flex items-center justify-center overflow-hidden" style="aspect-ratio: 16/9;">
                @if($camera->status == 'online')
                    <img src="{{ $streamUrl }}" 
                         alt="Stream {{ $camera->camera_name }}"
                         class="w-full h-full object-contain absolute inset-0 transition duration-300 group-hover:scale-105"
                         onerror="this.onerror=null; this.parentElement.classList.add('text-white'); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    
                    <!-- UI Error (Sembunyi secara default) -->
                    <div style="display: none;" class="flex-col items-center justify-center w-full h-full absolute inset-0 bg-slate-900 z-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <p class="text-slate-400 text-sm">Koneksi Terputus</p>
                    </div>

                    <!-- Indikator LIVE -->
                    <div class="absolute top-3 right-3 z-10 pointer-events-none">
                        <span class="bg-black/50 text-white px-2 py-1 rounded text-xs backdrop-blur-md flex items-center gap-1.5 border border-white/10">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> LIVE
                        </span>
                    </div>

                    <!-- Tombol Overlay Perbesar (Muncul saat di-hover) -->
                    <a href="{{ route('user.live.single', $camera->id) }}" class="absolute inset-0 bg-blue-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center z-20">
                        <span class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium shadow-lg backdrop-blur-sm transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                            Perbesar Kamera
                        </span>
                    </a>
                @else
                    <!-- Tampilan Jika Offline -->
                    <div class="text-slate-500 flex flex-col items-center z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium">Offline</span>
                    </div>
                @endif
            </div>

            <!-- Footer Kamera -->
            <div class="p-4 bg-white flex justify-between items-center border-t border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-800">{{ $camera->camera_name }}</h3>
                    <p class="text-xs text-slate-500">{{ $camera->connection_type }} | {{ $camera->ip_address ?? 'Otomatis' }}</p>
                </div>
                
                @if($camera->status == 'online')
                    <span class="text-green-500 bg-green-50 p-2 rounded-full" title="Online">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <span class="text-red-400 bg-red-50 p-2 rounded-full" title="Offline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>

        </div>
    @empty
        <div class="col-span-full bg-slate-50 border border-slate-200 p-12 rounded-2xl flex flex-col items-center justify-center text-center">
            <span class="text-6xl mb-4">📭</span>
            <h3 class="text-xl font-bold text-slate-700">Belum Ada Kamera</h3>
            <p class="text-slate-500 mt-2 max-w-md">Tambahkan kamera terlebih dahulu melalui menu "Kamera Saya" agar dapat memantau rekaman di halaman ini.</p>
            <a href="{{ route('user.cameras.index') }}" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
                Kelola Kamera
            </a>
        </div>
    @endforelse
</div>

@endsection