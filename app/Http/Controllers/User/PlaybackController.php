<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Camera;
use App\Models\Recording;
use Illuminate\Support\Facades\Auth;

class PlaybackController extends Controller
{
    // Fungsi ini menampilkan semua video sekaligus menangani form filter
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Ambil daftar kamera untuk dropdown filter
        $cameras = Camera::where('user_id', $userId)->get();

        // 1. Query Dasar: Ambil rekaman milik user yang sedang login
        $query = Recording::whereHas('camera', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->orderBy('start_time', 'desc');

        // 2. Jika user memfilter berdasarkan Kamera
        if ($request->filled('camera_id')) {
            $query->where('camera_id', $request->camera_id);
        }

        // 3. Jika user memfilter berdasarkan Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('start_time', $request->tanggal);
        }

        // 4. Eksekusi query (misal tampilkan 12 rekaman per halaman)
        $recordings = $query->paginate(12);

        // Pertahankan parameter filter di URL saat pindah halaman (pagination)
        $recordings->appends($request->all());

        return view('user.playback.index', compact('cameras', 'recordings'));
    }

    // Fungsi untuk streaming file video mp4 ke browser
    public function streamVideo($id)
    {
        $recording = Recording::findOrFail($id);
        
        if ($recording->camera->user_id != Auth::id()) abort(403, 'Akses ditolak.');

        // INI KUNCI KESESUAIAN DENGAN PROPOSALMU:
        // Menggabungkan path "storage/recordings/" dengan path dari database (misal "user_1/kamera_1/...")
        $path = storage_path('recordings/' . $recording->file_path); 

        if (file_exists($path)) {
            return response()->file($path);
        }
        
        abort(404, 'File video fisik tidak ditemukan di server.');
    }
}