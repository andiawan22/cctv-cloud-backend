@extends('layouts.admin')

@section('title','Edit User')

@section('content')

<h1 class="text-3xl font-bold mb-6">

Edit User

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

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">

    @csrf
    @method('PUT')

    <div class="grid grid-cols-2 gap-6">

        <div>
            <label class="block mb-2 font-medium">Nama Lengkap</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $user->name) }}"
                class="w-full border rounded-lg px-4 py-2"
                required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Username</label>
            <input
                type="text"
                name="username"
                value="{{ old('username', $user->username) }}"
                class="w-full border rounded-lg px-4 py-2"
                required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                class="w-full border rounded-lg px-4 py-2"
                required>
        </div>

        <div>
            <label class="block mb-2 font-medium">
                Password Baru
            </label>

            <input
                type="password"
                name="password"
                class="w-full border rounded-lg px-4 py-2">

            <small class="text-gray-500">
                Kosongkan jika tidak ingin mengubah password.
            </small>

        </div>

        <div>

    <label class="block mb-2 font-medium">
        Paket Layanan
    </label>

    <select
        name="package_id"
        class="w-full border rounded-lg px-4 py-2">

        @foreach($packages as $package)

        <option
            value="{{ $package->id }}"
            {{ old('package_id', $user->package_id) == $package->id ? 'selected' : '' }}>

            {{ $package->name }}
            ({{ $package->storage_limit_gb }} GB |
             {{ $package->camera_limit }} Kamera)

        </option>

        @endforeach

    </select>

</div>

        <div>

            <label class="block mb-2 font-medium">
                Status
            </label>

            <select
                name="status"
                class="w-full border rounded-lg px-4 py-2">

                <option value="active"
    {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
    Aktif
</option>

<option value="inactive"
    {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
    Nonaktif
</option>

            </select>

        </div>

    </div>

    <div class="mt-8 flex gap-3">

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

            Update User

        </button>

        <a href="{{ route('admin.users.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

            Batal

        </a>

    </div>

</form>

</div>


@endsection