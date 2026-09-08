@extends('layouts.user')

@section('title', 'Hasil Playback')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-slate-800">Hasil Rekaman</h1>
    <p class="text-slate-500 mt-1">Kamera: <span class="font-semibold">{{ $kamera->camera_name }}</span> | Tanggal: <span class="font-semibold">{{ $tanggal }}</span></p>
</div>

<!-- Tombol Kembali -->
<a href="{{ route('user.playback.index') }}" class="inline-flex items-center gap-2 mb-6 text-blue-600 hover:text-blue-800 font-medium transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
    </svg>
    Kembali ke Pencarian
</a>

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-semibold mb-6 border-b pb-3">Daftar Potongan Rekaman (Event)</h2>
    
    @if(count($eventFolders) > 0)
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($eventFolders as $event)
                <div class="border rounded-lg p-4 flex flex-col items-center justify-center hover:bg-slate-50 hover:border-blue-300 transition cursor-pointer shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <h3 class="font-medium text-slate-700">Event #{{ $event }}</h3>
                    <!-- Nanti kita aktifkan fitur putar videonya di sini -->
                    <!-- Link menuju pemutar video -->
                    <a href="{{ route('user.playback.play', ['camera_id' => $kamera->id, 'tanggal' => $tanggal, 'event_id' => $event]) }}" 
                       class="mt-3 text-sm text-center bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full font-medium hover:bg-blue-100 transition w-full inline-block">
                        Lihat Video
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-10">
            <p class="text-slate-500">Folder ditemukan, tapi belum ada file rekaman di dalamnya.</p>
        </div>
    @endif
</div>
@endsection