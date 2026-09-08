<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorageUsage;
use App\Models\User; // Tambahkan ini
use App\Models\Camera;
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use Illuminate\Http\Request;

class StorageController extends Controller
{
    /**
     * =======================================================
     * Menampilkan penggunaan storage seluruh user.
     * =======================================================
     */
    public function index()
    {
        // Ambil data storage user
        $storages = StorageUsage::with(['user.package'])->get();

        foreach ($storages as $storage) {
            $folderName = 'user_' . $storage->user_id;
            $folderPath = storage_path('recordings/' . $folderName);
            
            $sizeInBytes = 0;

            // Jika foldernya ada, kita suruh Linux yang menimbang isinya
            if (file_exists($folderPath)) {
                // Perintah Linux: du -sbL /path/folder | cut -f1
                // Menggunakan 'cut' jauh lebih aman dari bentrok variabel PHP
                $command = "du -sbL " . escapeshellarg($folderPath) . " 2>/dev/null | cut -f1";
                
                // Eksekusi perintah di terminal Linux secara diam-diam
                $output = shell_exec($command);
                
                // Jika hasilnya angka, masukkan sebagai bytes
                if (is_numeric(trim($output))) {
                    $sizeInBytes = (float) trim($output);
                }
            }

            // Konversi dari Bytes ke GigaBytes (GB)
            // 1 GB = 1,073,741,824 bytes
            $sizeInGb = $sizeInBytes / 1073741824;

            // Simpan hasil timbangan terbaru ke database
            $storage->update([
                'used_storage_gb' => $sizeInGb,
                'last_updated' => now(),
            ]);
        }

        // Panggil ulang data terbaru setelah di-update ukurannya
        $storages = StorageUsage::with(['user.package'])->get();

        return view('admin.storage.index', compact('storages'));
    }

    /**
     * =======================================================
     * Sesuai Flowchart: Membuat Folder Storage Fisik untuk User
     * =======================================================
     */
    public function generateFolders()
    {
        // 1. Tentukan lokasi asli ZoneMinder menyimpan rekaman di server Ubuntu-mu
        // (Biasanya ada di path ini, nanti kita sesuaikan jika beda)
        $zmEventsPath = '/var/cache/zoneminder/events'; 

        $users = User::where('role', 'user')->get();
        $countFolders = 0;
        $countLinks = 0;

        foreach ($users as $user) {
            $folderName = 'user_' . $user->id;

            // 2. Buat folder utama user (seperti kode sebelumnya)
            if (!Storage::disk('cctv_storage')->exists($folderName)) {
                Storage::disk('cctv_storage')->makeDirectory($folderName);
                $countFolders++;
            }

            // Dapatkan path lengkap (/var/www/.../storage/recordings/user_N)
            $userFolderPath = storage_path('recordings/' . $folderName);

            // 3. Cari semua kamera milik user ini
            $kameras = Camera::where('user_id', $user->id)->get();

            foreach ($kameras as $kamera) {
                // Pastikan kamera sudah punya ID Monitor dari ZoneMinder
                if (!empty($kamera->zoneminder_monitor_id)) {
                    
                    // Alamat asli rekaman kamera di dalam ZoneMinder
                    $sumberZM = $zmEventsPath . '/' . $kamera->zoneminder_monitor_id;
                    
                    // Alamat jalan pintas (shortcut) di dalam folder user
                    $jalanPintas = $userFolderPath . '/kamera_' . $kamera->id;

                    // 4. Jika sumbernya ada dan jalan pintas belum dibuat, maka buat!
                    if (file_exists($sumberZM) && !file_exists($jalanPintas)) {
                        symlink($sumberZM, $jalanPintas);
                        $countLinks++;
                    }
                }
            }
        }

        return redirect()->back()->with('success', "Berhasil! $countFolders folder user dan $countLinks jembatan kamera telah tersinkronisasi.");
    }
}