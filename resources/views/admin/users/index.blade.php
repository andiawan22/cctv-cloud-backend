@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')

@if(session('success'))

<div class="mb-6 bg-green-100 border border-green-300 text-green-700 rounded-lg p-4">
    {{ session('success') }}
</div>

@endif

<div class="flex justify-between items-center mb-6">

    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Manajemen User
        </h1>

        <p class="text-slate-500 mt-1">
            Kelola seluruh akun pengguna Cloud CCTV
        </p>
    </div>

    <a href="{{ route('admin.users.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow transition">

        + Tambah User

    </a>

</div>

<div class="bg-white rounded-xl shadow overflow-hidden">

<table class="w-full">

    <thead class="bg-slate-100">

        <tr>

            <th class="p-4 text-left">No</th>
            <th class="p-4 text-left">Nama</th>
            <th class="p-4 text-left">Username</th>
            <th class="p-4 text-left">Email</th>
            <th class="p-4 text-left">Paket</th>
            <th class="p-4 text-left">Storage</th>
            <th class="p-4 text-left">Kamera</th>
            <th class="p-4 text-left">Status</th>
            <th class="p-4 text-center">Aksi</th>

        </tr>

    </thead>

    <tbody>

    @forelse($users as $user)

    <tr class="border-t hover:bg-slate-50 transition">

        <td class="p-4">
            {{ $loop->iteration }}
        </td>

        <td class="p-4 font-medium">
            {{ $user->name }}
        </td>

        <td class="p-4">
            {{ $user->username }}
        </td>

        <td class="p-4">
            {{ $user->email }}
        </td>

        {{-- Paket --}}
      <td class="p-4">

@if($user->role == 'admin')

    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
        👑 Administrator
    </span>

@else

    @switch($user->package->name)

        @case('Basic')

            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                🔵 Basic
            </span>

            @break

        @case('Medium')

            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                🟡 Medium
            </span>

            @break

        @case('Premium')

            <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">
                🟣 Premium
            </span>

            @break

        @default

            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                {{ $user->package->name }}
            </span>

    @endswitch

@endif

</td>
        {{-- Storage --}}
        <td class="p-4">

@if($user->role == 'admin')
    ♾️ Unlimited
@else
    {{ $user->package->storage_limit_gb }} GB
@endif

</td>

        {{-- Kamera --}}
        <td class="p-4">

@if($user->role == 'admin')
    ♾️ Unlimited
@else
    {{ $user->package->camera_limit }}
@endif

</td>

        {{-- Status --}}
        <td class="p-4">

            @if($user->status == 'active')

                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                    🟢 Aktif
                </span>

            @else

                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                    🔴 Nonaktif
                </span>

            @endif

        </td>

        {{-- Aksi --}}
        <td class="p-4 text-center">

            <a href="{{ route('admin.users.edit',$user->id) }}"
               class="inline-flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white w-16 h-12 rounded-lg">

                ✏️ Edit

            </a>

            <form action="{{ route('admin.users.destroy',$user->id) }}"
                  method="POST"
                  class="inline-block ml-2"
                  onsubmit="return confirm('Yakin ingin menghapus user ini?')">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white w-20 h-12 rounded-lg">

                    🗑️ Hapus

                </button>

            </form>

        </td>

    </tr>

    @empty

    <tr>

        <td colspan="9" class="text-center py-10 text-slate-500">

            Belum ada data user.

        </td>

    </tr>

    @endforelse

    </tbody>

</table>

</div>

@endsection