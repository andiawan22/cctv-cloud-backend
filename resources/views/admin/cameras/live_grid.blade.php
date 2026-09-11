@extends('layouts.admin')

@section('title', 'Monitoring CCTV')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Monitoring CCTV</h1>
        <p class="text-slate-500 mt-1">Pantau seluruh kamera Cloud CCTV dari semua pengguna.</p>
    </div>

    <!-- FITUR FILTER PENGGUNA -->
    <form action="{{ route('admin.live.grid') }}" method="GET" class="w-full sm:w-auto">
        <select name="user_id" onchange="this.form.submit()" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 shadow-sm font-medium">
            <option value="">-- Semua Pemilik Kamera --</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $selectedUser == $u->id ? 'selected' : '' }}>
                    👤 {{ $u->name }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($cameras as $camera)
        @php
            // Merakit URL Streaming menggunakan Relative Path untuk melewati Nginx Proxy
            $streamUrl = '';
            if($camera->status == 'online' && $camera->zoneminder_monitor_id) {
                // Menghapus $zmHost dan langsung menggunakan /zm/...
                $streamUrl = '/zm/cgi-bin/nph-zms?mode=jpeg&monitor=' . $camera->zoneminder_monitor_id;
                if (!empty($zmToken)) { $streamUrl .= '&token=' . $zmToken; } 
                else { $streamUrl .= '&user=' . $zmUser . '&pass=' . $zmPass; }
            }
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col group">
            <div class="bg-slate-900 relative flex items-center justify-center overflow-hidden" style="aspect-ratio: 16/9;">
                @if($camera->status == 'online')
                    <img src="{{ $streamUrl }}" alt="Stream {{ $camera->camera_name }}" class="w-full h-full object-contain absolute inset-0 transition duration-300 group-hover:scale-105" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    
                    <div style="display: none;" class="flex-col items-center justify-center w-full h-full absolute inset-0 bg-slate-900 z-0">
                        <p class="text-slate-400 text-sm">Koneksi Terputus</p>
                    </div>

                    <div class="absolute top-3 right-3 z-10 pointer-events-none">
                        <span class="bg-black/50 text-white px-2 py-1 rounded text-xs flex items-center gap-1.5 border border-white/10">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> LIVE
                        </span>
                    </div>

                    <a href="{{ route('admin.live.single', $camera->id) }}" class="absolute inset-0 bg-blue-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center z-20">
                        <span class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">Perbesar Kamera</span>
                    </a>
                @else
                    <div class="text-slate-500 flex flex-col items-center z-10">
                        <span class="text-sm font-medium">Offline</span>
                    </div>
                @endif
            </div>

            <div class="p-4 bg-white flex justify-between items-center border-t border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-800">{{ $camera->camera_name }}</h3>
                    <p class="text-xs text-blue-600 font-medium">Pemilik: {{ $camera->user->name ?? 'Tidak diketahui' }}</p>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center p-10 text-slate-500">
            Belum ada kamera yang terdaftar di sistem.
        </div>
    @endforelse
</div>
@endsection