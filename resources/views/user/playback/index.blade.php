@extends('layouts.user')
@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Playback Rekaman</h2>
    <p class="text-gray-500">Pantau dan putar ulang histori rekaman CCTV Anda.</p>
</div>

<!-- Kotak Filter -->
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-8">
    <form action="{{ route('user.playback.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        
        <div class="flex-1 w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kamera</label>
            <select name="camera_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Semua Kamera --</option>
                @foreach($cameras as $cam)
                    <option value="{{ $cam->id }}" {{ request('camera_id') == $cam->id ? 'selected' : '' }}>
                        {{ $cam->camera_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition w-full md:w-auto">
                Cari
            </button>
            @if(request()->has('camera_id') || request()->has('tanggal'))
                <a href="{{ route('user.playback.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition w-full md:w-auto text-center">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Daftar Video -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($recordings as $rekaman)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- HTML5 Video Player -->
            <video class="w-full aspect-video bg-black" controls preload="metadata">
                <source src="{{ route('user.playback.stream', $rekaman->id) }}" type="video/mp4">
                Browser Anda tidak mendukung tag video.
            </video>
            
            <div class="p-4">
                <h3 class="font-bold text-gray-800">{{ $rekaman->camera->camera_name }}</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Tanggal: {{ \Carbon\Carbon::parse($rekaman->start_time)->format('d M Y - H:i:s') }}
                </p>
                <div class="mt-3 flex justify-between items-center text-xs">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ strtoupper($rekaman->record_mode) }}</span>
                    <span class="text-gray-500">{{ round($rekaman->file_size / 1048576, 2) }} MB</span>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded-lg border border-gray-200">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <p>Tidak ada data rekaman ditemukan.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $recordings->links() }}
</div>

@endsection