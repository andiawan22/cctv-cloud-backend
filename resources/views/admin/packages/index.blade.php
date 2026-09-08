@extends('layouts.admin')

@section('title','Paket Layanan')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-300 text-green-700 rounded-lg p-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-100 border border-red-300 text-red-700 rounded-lg p-4">
    {{ session('error') }}
</div>
@endif

<div class="flex justify-between items-center mb-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            Paket Layanan
        </h1>

        <p class="text-slate-500 mt-1">
            Kelola seluruh paket Cloud CCTV
        </p>

    </div>

    <a href="{{ route('admin.packages.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow">

        + Tambah Paket

    </a>

</div>

<div class="bg-white rounded-xl shadow overflow-hidden">

<table class="w-full">

<thead class="bg-slate-100">

<tr>

<th class="p-4 text-center">No</th>
<th class="p-4">Nama Paket</th>
<th class="p-4 text-center">Storage</th>
<th class="p-4 text-center">Kamera</th>
<th class="p-4 text-center">Custom</th>
<th class="p-4 text-center">Status</th>
<th class="p-4 text-center">Aksi</th>

</tr>

</thead>

<tbody>

@forelse($packages as $package)

<tr class="border-t hover:bg-slate-50">

<td class="p-4 text-center">

{{ $loop->iteration }}

</td>

<td class="p-4">

{{ $package->name }}

</td>

<td class="p-4 text-center">

{{ $package->storage_limit_gb }} GB

</td>

<td class="p-4 text-center">

{{ $package->camera_limit }}

</td>

<td class="p-4 text-center">

@if($package->is_custom)

<span class="text-green-600 font-semibold">
Ya
</span>

@else

Tidak

@endif

</td>

<td class="p-4 text-center">

@if($package->status)

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

Aktif

</span>

@else

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

Nonaktif

</span>

@endif

</td>

<td class="p-4 text-center">

    <div class="flex justify-center gap-2">

        <a href="{{ route('admin.packages.edit',$package->id) }}"
           class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">

            ✏️
            <span>Edit</span>

        </a>

        <form
            action="{{ route('admin.packages.destroy',$package->id) }}"
            method="POST">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                onclick="return confirm('Yakin ingin menghapus paket ini?')"
                class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">

                🗑️
                <span>Hapus</span>

            </button>

        </form>

    </div>

</td>

</tr>

@empty

<tr>

<td colspan="7" class="text-center py-10">

Belum ada paket.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

@endsection