@extends('layouts.admin')

@section('title','Tambah Kamera')

@section('content')

<div class="flex items-center justify-between mb-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">

            Tambah Kamera

        </h1>

        <p class="text-slate-500 mt-1">

            Tambahkan kamera CCTV baru

        </p>

    </div>

    <a href="{{ route('admin.cameras.index') }}"
       class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg">

        Kembali

    </a>

</div>


@if ($errors->any())

<div class="mb-6 bg-red-100 border border-red-300 text-red-700 rounded-lg p-4">

    <strong>Terjadi Kesalahan</strong>

    <ul class="list-disc ml-6 mt-2">

        @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif


<div class="bg-white rounded-xl shadow p-8">

<form method="POST" action="{{ route('admin.cameras.store') }}">

@csrf

<div class="grid grid-cols-2 gap-6">

    {{-- ========================= --}}
    {{-- User Pemilik Kamera --}}
    {{-- ========================= --}}

    <div>

        <label class="block mb-2 font-medium">

            Pemilik Kamera

        </label>

        <select
            name="user_id"
            class="w-full border rounded-lg px-4 py-2"
            required>

            @foreach($users as $user)

            <option
                value="{{ $user->id }}"
                {{ old('user_id') == $user->id ? 'selected' : '' }}>

                {{ $user->name }}

            </option>

            @endforeach

        </select>

    </div>


    {{-- ========================= --}}
    {{-- Nama Kamera --}}
    {{-- ========================= --}}

    <div>

        <label class="block mb-2 font-medium">

            Nama Kamera

        </label>

        <input
            type="text"
            name="camera_name"
            value="{{ old('camera_name') }}"
            class="w-full border rounded-lg px-4 py-2"
            required>

    </div>


    {{-- ========================= --}}
    {{-- Jenis Koneksi --}}
    {{-- ========================= --}}

    <div>

        <label class="block mb-2 font-medium">

            Jenis Koneksi

        </label>

        <select
            id="connection_type"
            name="connection_type"
            class="w-full border rounded-lg px-4 py-2">

            <option value="RTSP">

                RTSP

            </option>

            <option value="ONVIF">

                ONVIF

            </option>

        </select>

    </div>


</div>

{{-- ====================================================== --}}
{{-- FORM ONVIF --}}
{{-- ====================================================== --}}

<div id="onvif-form" class="grid grid-cols-2 gap-6 mt-6">

    <div>

        <label class="block mb-2 font-medium">

            IP Address

        </label>

        <input
            type="text"
            name="ip_address"
            value="{{ old('ip_address') }}"
            class="w-full border rounded-lg px-4 py-2">

    </div>

    <div>

        <label class="block mb-2 font-medium">

            Username Kamera

        </label>

        <input
            type="text"
            name="camera_username"
            value="{{ old('camera_username') }}"
            class="w-full border rounded-lg px-4 py-2">

    </div>

    <div>

        <label class="block mb-2 font-medium">

            Password Kamera

        </label>

        <input
            type="password"
            name="camera_password"
            class="w-full border rounded-lg px-4 py-2">

    </div>

</div>



{{-- ====================================================== --}}
{{-- FORM RTSP --}}
{{-- ====================================================== --}}

<div id="rtsp-form" class="mt-6">

    <label class="block mb-2 font-medium">

        RTSP URL

    </label>

    <textarea
        name="rtsp_url"
        rows="3"
        class="w-full border rounded-lg px-4 py-2">{{ old('rtsp_url') }}</textarea>

</div>


<div class="mt-8 flex gap-3">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

Simpan Kamera

</button>

<a href="{{ route('admin.cameras.index') }}"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Batal

</a>

</div>

</form>

</div>



<script>

const jenis = document.getElementById('connection_type');

const rtsp = document.getElementById('rtsp-form');

const onvif = document.getElementById('onvif-form');

function updateForm(){

    if(jenis.value=="RTSP"){

        rtsp.style.display="block";

        onvif.style.display="none";

    }

    else{

        rtsp.style.display="none";

        onvif.style.display="grid";

    }

}

jenis.addEventListener("change",updateForm);

updateForm();

</script>

@endsection