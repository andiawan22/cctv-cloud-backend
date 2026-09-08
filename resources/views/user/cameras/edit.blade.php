@extends('layouts.user')

@section('title','Edit Kamera')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Edit Kamera
</h1>

@if ($errors->any())

<div class="mb-6 bg-red-100 border border-red-300 text-red-700 rounded-lg p-4">

    <strong>Terjadi kesalahan:</strong>

    <ul class="list-disc ml-6 mt-2">

        @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif


<div class="bg-white rounded-xl shadow p-8">

<form method="POST" action="{{ route('user.cameras.update',$camera->id) }}">

    @csrf
    @method('PUT')

    <div class="grid grid-cols-2 gap-6">

        {{-- Nama Kamera --}}
        <div>

            <label class="block mb-2 font-medium">
                Nama Kamera
            </label>

            <input
                type="text"
                name="camera_name"
                value="{{ old('camera_name',$camera->camera_name) }}"
                class="w-full border rounded-lg px-4 py-2"
                required>

        </div>

        {{-- Jenis Koneksi --}}
        <div>

            <label class="block mb-2 font-medium">
                Jenis Koneksi
            </label>

            <select
                name="connection_type"
                class="w-full border rounded-lg px-4 py-2">

                <option value="ONVIF"
                    {{ old('connection_type',$camera->connection_type)=='ONVIF' ? 'selected' : '' }}>
                    ONVIF
                </option>

                <option value="RTSP"
                    {{ old('connection_type',$camera->connection_type)=='RTSP' ? 'selected' : '' }}>
                    RTSP
                </option>

            </select>

        </div>

        {{-- Mode Rekaman --}}
        <div>
            <label class="block mb-2 font-medium">Mode Rekaman</label>
            <select name="record_mode" class="w-full border rounded-lg px-4 py-2" required>
                <option value="Monitor" {{ old('record_mode', $camera->record_mode ?? '') == 'Monitor' ? 'selected' : '' }}>Monitor (Live View Saja)</option>
                <option value="Modect" {{ old('record_mode', $camera->record_mode ?? '') == 'Modect' ? 'selected' : '' }}>Modect (Deteksi Gerak)</option>
                <option value="Record" {{ old('record_mode', $camera->record_mode ?? '') == 'Record' ? 'selected' : '' }}>Record (Rekam 24/7)</option>
                <option value="Mocord" {{ old('record_mode', $camera->record_mode ?? '') == 'Mocord' ? 'selected' : '' }}>Mocord (24/7 + Gerak)</option>
            </select>
        </div>

        {{-- Resolusi Kamera --}}
        <div>
            <label class="block mb-2 font-medium">Resolusi Video</label>
            <select name="resolution" class="w-full border rounded-lg px-4 py-2" required>
                <option value="1920x1080" {{ old('resolution', $camera->resolution ?? '') == '1920x1080' ? 'selected' : '' }}>1080p (Full HD)</option>
                <option value="1280x720" {{ old('resolution', $camera->resolution ?? '') == '1280x720' ? 'selected' : '' }}>720p (HD)</option>
                <option value="640x480" {{ old('resolution', $camera->resolution ?? '') == '640x480' ? 'selected' : '' }}>VGA (Standar)</option>
            </select>
        </div>

        {{-- IP --}}
        <div>

            <label class="block mb-2 font-medium">
                IP Address
            </label>

            <input
                type="text"
                name="ip_address"
                value="{{ old('ip_address',$camera->ip_address) }}"
                class="w-full border rounded-lg px-4 py-2">

        </div>

        {{-- RTSP --}}
        <div>

            <label class="block mb-2 font-medium">
                RTSP URL
            </label>

            <input
                type="text"
                name="rtsp_url"
                value="{{ old('rtsp_url',$camera->rtsp_url) }}"
                class="w-full border rounded-lg px-4 py-2">

        </div>

        {{-- Username Kamera --}}
        <div>

            <label class="block mb-2 font-medium">
                Username Kamera
            </label>

            <input
                type="text"
                name="camera_username"
                value="{{ old('camera_username',$camera->camera_username) }}"
                class="w-full border rounded-lg px-4 py-2">

        </div>

        {{-- Password Kamera --}}
        <div>

            <label class="block mb-2 font-medium">
                Password Kamera
            </label>

            <input
                type="text"
                name="camera_password"
                value="{{ old('camera_password',$camera->camera_password) }}"
                class="w-full border rounded-lg px-4 py-2">

        </div>

    </div>

    <div class="mt-8 flex gap-3">

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

            Update Kamera

        </button>

        <a href="{{ route('user.cameras.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

            Batal

        </a>

    </div>

</form>

</div>

@endsection