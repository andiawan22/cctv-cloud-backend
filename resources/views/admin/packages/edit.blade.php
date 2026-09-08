@extends('layouts.admin')

@section('title','Edit Paket')

@section('content')

<div class="flex items-center justify-between mb-6">

    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Edit Paket
        </h1>

        <p class="text-slate-500 mt-1">
            Ubah informasi paket layanan Cloud CCTV
        </p>
    </div>

    <a href="{{ route('admin.packages.index') }}"
       class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg">

        Kembali

    </a>

</div>

@if($errors->any())

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

<form method="POST" action="{{ route('admin.packages.update',$package->id) }}">

@csrf
@method('PUT')

<div class="grid grid-cols-2 gap-6">

    <div>

        <label class="block mb-2 font-medium">

            Nama Paket

        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name',$package->name) }}"
            class="w-full border rounded-lg px-4 py-2"
            required>

    </div>

    <div>

        <label class="block mb-2 font-medium">

            Storage (GB)

        </label>

        <input
            type="number"
            name="storage_limit_gb"
            value="{{ old('storage_limit_gb',$package->storage_limit_gb) }}"
            class="w-full border rounded-lg px-4 py-2"
            required>

    </div>

    <div>

        <label class="block mb-2 font-medium">

            Maksimal Kamera

        </label>

        <input
            type="number"
            name="camera_limit"
            value="{{ old('camera_limit',$package->camera_limit) }}"
            class="w-full border rounded-lg px-4 py-2"
            required>

    </div>

    <div>

        <label class="block mb-2 font-medium">

            Status

        </label>

        <select
            name="status"
            class="w-full border rounded-lg px-4 py-2">

            <option value="1"
                {{ old('status',$package->status)==1 ? 'selected' : '' }}>

                Aktif

            </option>

            <option value="0"
                {{ old('status',$package->status)==0 ? 'selected' : '' }}>

                Nonaktif

            </option>

        </select>

    </div>

    <div>

        <label class="block mb-2 font-medium">

            Paket Custom?

        </label>

        <select
            name="is_custom"
            class="w-full border rounded-lg px-4 py-2">

            <option value="0"
                {{ old('is_custom',$package->is_custom)==0 ? 'selected' : '' }}>

                Tidak

            </option>

            <option value="1"
                {{ old('is_custom',$package->is_custom)==1 ? 'selected' : '' }}>

                Ya

            </option>

        </select>

    </div>

</div>

<div class="mt-8 flex gap-3">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

Update Paket

</button>

<a href="{{ route('admin.packages.index') }}"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Batal

</a>

</div>

</form>

</div>

@endsection