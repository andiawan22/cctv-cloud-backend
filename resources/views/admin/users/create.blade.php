@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')

<div class="flex items-center justify-between mb-6">

    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Tambah User
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

        <p class="text-slate-500 mt-1">
            Tambahkan pengguna baru ke sistem Cloud CCTV
        </p>
    </div>

    <a href="{{ route('admin.users.index') }}"
       class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg">

        Kembali

    </a>

</div>

<div class="bg-white rounded-xl shadow p-8">

    <form method="POST" action="{{ route('admin.users.store') }}">
    @csrf

        <div class="grid grid-cols-2 gap-6">

            <div>

                <label class="block font-medium mb-2">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap"
                    class="w-full border rounded-lg px-4 py-2"
                    required>
            </div>

            <div>

                <label class="block font-medium mb-2">
                    Username
                </label>

                <input
                    
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    class="w-full border rounded-lg px-4 py-2"
                    required>

            </div>

            <div>

                <label class="block font-medium mb-2">
                    Email
                </label>

                <input
                    
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border rounded-lg px-4 py-2"
                    required>

            </div>

            <div>

                <label class="block font-medium mb-2">
                    Password
                </label>

                <input
                    
                    type="password"
                    name="password"
                    class="w-full border rounded-lg px-4 py-2"
                    required>
                    
            </div>
            

<div>

    <label class="block font-medium mb-2">
        Paket Layanan
    </label>

    <select
    id="package"
    name="package_id"
    class="w-full border rounded-lg px-4 py-2">

@foreach($packages as $package)

<option
    value="{{ $package->id }}"
    {{ old('package_id') == $package->id ? 'selected' : '' }}>  
    {{ $package->name }}
    ({{ $package->storage_limit_gb }} GB |
     {{ $package->camera_limit }} Kamera)
</option>
@endforeach

</select>
</div>



<div>

    <label class="block font-medium mb-2">
        Status
    </label>

    <select
        name="status"
        class="w-full border rounded-lg px-4 py-2">

        <option value="active"
    {{ old('status') == 'active' ? 'selected' : '' }}>
    Aktif
</option>

<option value="inactive"
    {{ old('status') == 'inactive' ? 'selected' : '' }}>
    Nonaktif
</option>

    </select>

</div>

        </div>

        <div class="mt-8 flex gap-3">

    <button
        type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

        Simpan User

    </button>

    <a href="{{ route('admin.users.index') }}"
       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

        Batal

    </a>

</div>
</form>


@endsection