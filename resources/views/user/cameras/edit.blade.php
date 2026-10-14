@extends('layouts.user')

@section('title', 'Edit Kamera - ' . $camera->camera_name)

@section('content')

<!-- Header Section -->
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3">
            <a href="{{ route('user.cameras.index') }}" class="text-slate-400 hover:text-blue-600 transition" title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                </svg>
            </a>
            Edit Konfigurasi Kamera
        </h1>
        <p class="text-slate-500 mt-1 ml-11">Perbarui pengaturan untuk kamera <span class="font-semibold text-slate-700">{{ $camera->camera_name }}</span>.</p>
    </div>
</div>

<!-- Error Handling -->
@if ($errors->any())
<div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-sm">
    <div class="flex items-center mb-2">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        <strong class="font-semibold">Terjadi Kesalahan:</strong>
    </div>
    <ul class="list-disc ml-7 text-sm space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Form Section -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-5xl">
    <div class="p-6 sm:p-8">
        <form method="POST" action="{{ route('user.cameras.update', $camera->id) }}">
            @csrf
            @method('PUT')

            <!-- KATEGORI 1: Info Dasar -->
            <h2 class="text-lg font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                Informasi Dasar
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                {{-- Nama Kamera --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-slate-700">Nama Kamera <span class="text-red-500">*</span></label>
                    <input type="text" name="camera_name" value="{{ old('camera_name', $camera->camera_name) }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="Contoh: Kamera Depan" required>
                </div>

                {{-- Jenis Koneksi --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-slate-700">Jenis Koneksi <span class="text-red-500">*</span></label>
                    <select name="connection_type" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white" required>
                        <option value="ONVIF" {{ old('connection_type', $camera->connection_type) == 'ONVIF' ? 'selected' : '' }}>ONVIF (Otomatis & Mendukung PTZ)</option>
                        <option value="RTSP" {{ old('connection_type', $camera->connection_type) == 'RTSP' ? 'selected' : '' }}>RTSP (Manual Streaming)</option>
                    </select>
                </div>
                
                {{-- Mode Rekaman --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-slate-700">Mode Rekaman <span class="text-red-500">*</span></label>
                    <select name="record_mode" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white" required>
                        <option value="Monitor" {{ old('record_mode', $camera->record_mode) == 'Monitor' ? 'selected' : '' }}>Monitor (Live View Saja)</option>
                        <option value="Modect" {{ old('record_mode', $camera->record_mode) == 'Modect' ? 'selected' : '' }}>Modect (Rekam saat ada gerakan)</option>
                        <option value="Record" {{ old('record_mode', $camera->record_mode) == 'Record' ? 'selected' : '' }}>Record (Rekam terus-menerus 24/7)</option>
                        <option value="Mocord" {{ old('record_mode', $camera->record_mode) == 'Mocord' ? 'selected' : '' }}>Mocord (24/7 + Deteksi gerakan)</option>
                    </select>
                </div>

                {{-- Resolusi Kamera --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-slate-700">Resolusi Video <span class="text-red-500">*</span></label>
                    <select name="resolution" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white" required>
                        <option value="1920x1080" {{ old('resolution', $camera->resolution) == '1920x1080' ? 'selected' : '' }}>1080p (Full HD)</option>
                        <option value="1280x720" {{ old('resolution', $camera->resolution) == '1280x720' ? 'selected' : '' }}>720p (HD)</option>
                        <option value="640x480" {{ old('resolution', $camera->resolution) == '640x480' ? 'selected' : '' }}>VGA (Standar)</option>
                    </select>
                </div>
            </div>

            <!-- KATEGORI 2: Konfigurasi Jaringan -->
            <h2 class="text-lg font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                Konfigurasi Jaringan & Autentikasi
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                {{-- IP Address --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-slate-700">IP Address</label>
                    <input type="text" name="ip_address" value="{{ old('ip_address', $camera->ip_address) }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="Contoh: 192.168.1.22">
                </div>

                {{-- Port ONVIF --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-slate-700">Port ONVIF</label>
                    <!-- [PERBAIKAN BUG] Pemanggilan variabel nilai port yang benar agar tidak selalu kembali ke 8899 -->
                    <input type="number" name="onvif_port" value="{{ old('onvif_port', $camera->onvif_port ?? 8899) }}" 
                        class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="Contoh: 8899">
                    <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Standar port ONVIF: 8899 (Bardi/Dings) atau 80 (Hikvision).
                    </p>
                </div>

                {{-- Username Kamera --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-slate-700">Username Kamera</label>
                    <input type="text" name="camera_username" value="{{ old('camera_username', $camera->camera_username) }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="admin">
                </div>

                {{-- Password Kamera --}}
                <div>
                    <label class="block mb-1.5 text-sm font-medium text-slate-700">Password Kamera</label>
                    <input type="text" name="camera_password" value="{{ old('camera_password', $camera->camera_password) }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="********">
                </div>

                {{-- RTSP URL (Melebar penuh 2 kolom) --}}
                <div class="md:col-span-2">
                    <label class="block mb-1.5 text-sm font-medium text-slate-700">RTSP URL <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="rtsp_url" value="{{ old('rtsp_url', $camera->rtsp_url) }}"
                        class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50"
                        placeholder="rtsp://username:password@ip_address:554/stream">
                    <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Jika memilih koneksi <strong>ONVIF</strong>, kosongkan saja kolom ini karena akan ditarik otomatis oleh sistem.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center gap-3 pt-6 border-t border-slate-100">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-blue-100 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('user.cameras.index') }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium px-6 py-2.5 rounded-lg shadow-sm transition-all">
                    Batal
                </a>
            </div>
            
        </form>
    </div>
</div>

@endsection