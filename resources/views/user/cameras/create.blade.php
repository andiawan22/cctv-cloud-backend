@extends('layouts.user')

@section('title','Tambah Kamera')

@section('content')

<div class="flex justify-between items-center mb-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            Tambah Kamera
        </h1>

        <p class="text-slate-500 mt-1">
            Tambahkan kamera CCTV baru ke akun Anda.
        </p>

    </div>

    <a href="{{ route('user.cameras.index') }}"
        class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

        Kembali

    </a>

</div>


@if ($errors->any())

<div class="mb-6 bg-red-100 border border-red-300 text-red-700 rounded-lg p-4">

    <strong>Terjadi kesalahan :</strong>

    <ul class="list-disc ml-6 mt-2">

        @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif


<div class="bg-white rounded-xl shadow p-8">

<form action="{{ route('user.cameras.store') }}" method="POST">

@csrf

<div class="grid grid-cols-2 gap-6">

    {{-- Nama Kamera --}}
    <div class="col-span-2">

        <label class="block font-medium mb-2">
            📷 Nama Kamera
        </label>

        <input
            type="text"
            name="camera_name"
            value="{{ old('camera_name') }}"
            placeholder="Contoh : Garasi, Ruang Tamu, Gudang"
            class="w-full border rounded-lg px-4 py-3">

        <p class="text-sm text-gray-500 mt-1">
            Nama ini digunakan untuk membedakan kamera pada dashboard.
        </p>

    </div>


    {{-- Jenis Koneksi --}}
    <div class="col-span-2">

        <label class="block font-medium mb-2">
            🔌 Jenis Koneksi
        </label>

        <select
            id="connection_type"
            name="connection_type"
            class="w-full border rounded-lg px-4 py-3">

            <option value="">-- Pilih Jenis Koneksi --</option>

            <option value="ONVIF"
                {{ old('connection_type')=="ONVIF" ? 'selected' : '' }}>

                ONVIF

            </option>

            <option value="RTSP"
                {{ old('connection_type')=="RTSP" ? 'selected' : '' }}>

                RTSP

            </option>

        </select>

        <p class="text-sm text-gray-500 mt-1">

            Pilih metode koneksi kamera.

        </p>

    </div>
    
    {{-- Mode Rekaman --}}
    <div>
        <label class="block font-medium mb-2">📹 Mode Rekaman</label>
        <select name="record_mode" class="w-full border rounded-lg px-4 py-3" required>
            <option value="Monitor" {{ old('record_mode') == 'Monitor' ? 'selected' : '' }}>Monitor (Live View Saja)</option>
            <option value="Modect" {{ old('record_mode') == 'Modect' ? 'selected' : '' }}>Modect (Deteksi Gerak)</option>
            <option value="Record" {{ old('record_mode') == 'Record' ? 'selected' : '' }}>Record (Rekam 24/7)</option>
            <option value="Mocord" {{ old('record_mode') == 'Mocord' ? 'selected' : '' }}>Mocord (24/7 + Gerak)</option>
        </select>
        <p class="text-sm text-gray-500 mt-1">Metode perekaman ZoneMinder.</p>
    </div>

    {{-- Resolusi Kamera --}}
    <div>
        <label class="block font-medium mb-2">📺 Resolusi Video</label>
        <select name="resolution" class="w-full border rounded-lg px-4 py-3" required>
            <option value="1920x1080" {{ old('resolution') == '1920x1080' ? 'selected' : '' }}>1080p (Full HD)</option>
            <option value="1280x720" {{ old('resolution') == '1280x720' ? 'selected' : '' }}>720p (HD)</option>
            <option value="640x480" {{ old('resolution') == '640x480' ? 'selected' : '' }}>VGA (Standar)</option>
        </select>
        <p class="text-sm text-gray-500 mt-1">Dimensi piksel streaming kamera.</p>
    </div>

{{-- port onviv --}}
    <div class="mb-4">
        <label class="block text-sm font-medium text-slate-700 mb-1">Port ONVIF</label>
        <input type="number" name="onvif_port" 
            value="{{ old('onvif_port', $camera->onvif_port ?? 8899) }}" 
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            required>
        <p class="text-xs text-gray-500 mt-1">Standar bawaan adalah 8899 atau 80.</p>
    </div>
    {{-- ===========================
            ONVIF
    ============================ --}}

<div id="onvifFields" class="col-span-2 hidden">

<div class="grid grid-cols-2 gap-6">

<div>

<label class="block font-medium mb-2">

🌐 IP Address

</label>

<input
type="text"
name="ip_address"
value="{{ old('ip_address') }}"
placeholder="Contoh : 192.168.1.100"
class="w-full border rounded-lg px-4 py-3">

<p class="text-sm text-gray-500 mt-1">

Masukkan alamat IP kamera.

</p>

</div>


<div>

<label class="block font-medium mb-2">

👤 Username Kamera

</label>

<input
type="text"
name="camera_username"
value="{{ old('camera_username') }}"
placeholder="Contoh : admin"
class="w-full border rounded-lg px-4 py-3">

<p class="text-sm text-gray-500 mt-1">

Username login kamera.

</p>

</div>


<div class="col-span-2">

<label class="block font-medium mb-2">

🔒 Password Kamera

</label>

<input
type="password"
name="camera_password"
placeholder="Masukkan password kamera"
class="w-full border rounded-lg px-4 py-3">

<p class="text-sm text-gray-500 mt-1">

Password digunakan untuk menghubungkan kamera.

</p>

</div>

</div>

</div>


{{-- ===========================
        RTSP
=========================== --}}

<div id="rtspFields" class="col-span-2 hidden">

<label class="block font-medium mb-2">

🎥 RTSP URL

</label>

<input
type="text"
name="rtsp_url"
value="{{ old('rtsp_url') }}"
placeholder="rtsp://admin:password@192.168.1.100:554/stream1"
class="w-full border rounded-lg px-4 py-3">

<p class="text-sm text-gray-500 mt-1">

Contoh :
rtsp://username:password@ip:554/stream1

</p>

</div>

</div>


<div class="mt-8 flex gap-3">

<button
type="submit"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

💾 Simpan Kamera

</button>

<a href="{{ route('user.cameras.index') }}"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Batal

</a>

</div>

</form>

</div>


<script>

document.addEventListener("DOMContentLoaded",function(){

const type=document.getElementById("connection_type");

const onvif=document.getElementById("onvifFields");

const rtsp=document.getElementById("rtspFields");

function toggleFields(){

if(type.value==="ONVIF"){

onvif.classList.remove("hidden");
rtsp.classList.add("hidden");

}else if(type.value==="RTSP"){

rtsp.classList.remove("hidden");
onvif.classList.add("hidden");

}else{

onvif.classList.add("hidden");
rtsp.classList.add("hidden");

}

}

toggleFields();

type.addEventListener("change",toggleFields);

});

</script>

@endsection