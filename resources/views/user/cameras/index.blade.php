@extends('layouts.user')

@section('title','Kamera Saya')

@section('content')

<!-- Menampilkan Pesan Sukses -->
@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-300 text-green-700 rounded-lg p-4">
    {{ session('success') }}
</div>
@endif

<!-- Menampilkan Pesan Error (misal: Gagal Konek ke ZoneMinder) -->
@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-300 text-red-700 rounded-lg p-4">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Kamera Saya
        </h1>
        <p class="text-slate-500 mt-1">
            Daftar kamera yang terhubung ke akun Anda.
        </p>
    </div>

    <!-- Wrapper Flex untuk menyejajarkan kedua tombol -->
    <div class="flex gap-3">
        <!-- Tombol Sinkronisasi Status User -->
        <form action="{{ route('user.cameras.sync_status') }}" method="POST">
            @csrf
            <button type="submit" onclick="return confirm('Mulai Refresh seluruh status Camera Cctv?')" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-3 rounded-lg shadow font-medium">
                🔄 Refresh Status
            </button>
        </form>

        <!-- Tombol Tambah Kamera -->
        <a href="{{ route('user.cameras.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow font-medium">
            + Tambah Kamera
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-4 text-center">No</th>
                <th class="p-4 text-left">Nama Kamera</th>
                <th class="p-4 text-left">IP Address</th>
                <th class="p-4 text-center">Protocol</th>
                <th class="p-4 text-center">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cameras as $camera)
            <tr class="border-t hover:bg-slate-50">
                <td class="p-4 text-center">
                    {{ $loop->iteration }}
                </td>

                <td class="p-4 font-medium">
                    {{ $camera->camera_name }}
                </td>

                <td class="p-4">
                    {{ $camera->ip_address }}
                </td>

                <td class="p-4 text-center">
                    @if($camera->connection_type == 'RTSP')
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full inline-block min-w-[70px] text-sm font-semibold">
                        RTSP
                    </span>
                    @else
                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full inline-block min-w-[70px] text-sm font-semibold">
                        ONVIF
                    </span>
                    @endif
                </td>

                <td class="p-4 text-center">
                    @if($camera->status == 'online')
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full inline-block min-w-[90px] text-sm font-semibold">
                        🟢 Online
                    </span>
                    @else
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full inline-block min-w-[90px] text-sm font-semibold">
                        🔴 Offline
                    </span>
                    @endif
                </td>

                <td class="p-4 text-center">
                    <div class="flex justify-center gap-2">
                        
                        <!-- TOMBOL LIHAT LIVE VIEW (Hanya muncul jika kamera punya ID ZM) -->
                        @if($camera->zoneminder_monitor_id)
                        
                        @endif
                        <a href="{{ route('user.cameras.edit',$camera->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            ✏️ Edit
                        </a>

                        <form action="{{ route('user.cameras.destroy',$camera->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus kamera ini?')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-10 text-slate-500">
                    Belum ada kamera.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection