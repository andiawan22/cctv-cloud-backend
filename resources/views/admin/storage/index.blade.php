@extends('layouts.admin')

@section('title','Storage Server')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Storage Server</h1>
        <p class="text-slate-500 mt-1">Monitoring penggunaan storage seluruh pengguna Cloud CCTV.</p>
    </div>
    <form action="{{ route('admin.storage.generate') }}" method="POST">
        @csrf
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v1H2V6zm0 4v5a2 2 0 002 2h12a2 2 0 002-2v-5H2z" clip-rule="evenodd" />
            </svg>
            Sinkronisasi Folder Server
        </button>
    </form>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">

<table class="w-full">

    <thead class="bg-slate-100">

        <tr>

            <th class="p-4 text-left">No</th>

            <th class="p-4 text-left">User</th>

            <th class="p-4 text-left">Paket</th>

            <th class="p-4 text-center">Kuota</th>

            <th class="p-4 text-center">Terpakai</th>

            <th class="p-4 text-center">Sisa</th>

            <th class="p-4 text-center">Penggunaan</th>

        </tr>

    </thead>

    <tbody>

@forelse($storages as $storage)

@php

$quota = $storage->user->package->storage_limit_gb;

$used = $storage->used_storage_gb;

$remaining = $storage->remaining_storage_gb;

$percent = $quota > 0 ? ($used/$quota)*100 : 0;

@endphp

<tr class="border-t hover:bg-slate-50">

<td class="p-4">

{{ $loop->iteration }}

</td>

<td class="p-4 font-semibold">

{{ $storage->user->name }}

</td>

<td class="p-4">

{{ $storage->user->package->name }}

</td>

<td class="p-4 text-center">

{{ $quota }} GB

</td>

<td class="p-4 text-center">

{{ number_format($used,2) }} GB

</td>

<td class="p-4 text-center">

{{ number_format($remaining,2) }} GB

</td>

<td class="p-4">

<div class="w-full bg-gray-200 rounded-full h-3">

<div
class="bg-blue-600 h-3 rounded-full"
style="width: {{ $percent }}%;">

</div>

</div>

<div class="text-xs text-center mt-1">

{{ number_format($percent,1) }} %

</div>

</td>

</tr>

@empty

<tr>

<td colspan="7" class="text-center py-10 text-slate-500">

Belum ada data storage.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

@endsection