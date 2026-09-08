@extends('layouts.user')

@section('title', 'Putar Video')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-slate-800">Putar Rekaman</h1>
    <p class="text-slate-500 mt-1">Kamera: <span class="font-semibold">{{ $kamera->camera_name }}</span> | Tanggal: <span class="font-semibold">{{ $tanggal }}</span> | Event: <span class="font-semibold">#{{ $eventId }}</span></p>
</div>

<a href="{{ route('user.playback.show', ['camera_id' => $kamera->id, 'tanggal' => $tanggal]) }}" class="inline-flex items-center gap-2 mb-6 text-blue-600 hover:text-blue-800 font-medium transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
    </svg>
    Kembali ke Daftar Event
</a>

<div class="bg-black rounded-xl shadow overflow-hidden max-w-4xl mx-auto border border-slate-700">
    @if($videoFile)
        <!-- HTML5 Video Player -->
        <video controls autoplay class="w-full h-auto max-h-[70vh]">
            <source src="{{ route('user.playback.stream', ['camera' => $kamera->id, 'tanggal' => $tanggal, 'event' => $eventId, 'file' => $videoFile]) }}" type="video/mp4">
            Browser Anda tidak mendukung pemutar video HTML5.
        </video>
    @else
        <div class="flex flex-col items-center justify-center h-64 text-slate-300 p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="text-lg font-medium text-white mb-2">File Video MP4 Tidak Ditemukan</p>
            <p class="text-sm">Sepertinya ZoneMinder Anda masih disetting untuk menyimpan gambar (.jpg), bukan video.<br>Silakan ubah pengaturan ZoneMinder <b>(Tab Storage -> Video Writer -> H264 Passthrough)</b> untuk event selanjutnya.</p>
        </div>
    @endif
</div>
@endsection