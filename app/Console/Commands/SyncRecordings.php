<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Camera;
use App\Models\Recording;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SyncRecordings extends Command
{
    // Nama perintah yang akan dipanggil di terminal
    protected $signature = 'cctv:sync-recordings';
    protected $description = 'Sinkronisasi file video fisik dari folder ke database recordings';

    public function handle()
    {
        $this->info('Memulai sinkronisasi rekaman...');
        
        $cameras = Camera::all();
        $videoBaru = 0;

        foreach ($cameras as $camera) {
            // Arahkan ke folder: storage/recordings/user_X/kamera_Y
            $basePath = storage_path("recordings/user_{$camera->user_id}/kamera_{$camera->id}");

            if (!File::exists($basePath)) continue;

            // 1. Scan folder Tanggal (contoh: 2026-09-15)
            $dateFolders = File::directories($basePath);
            foreach ($dateFolders as $dateFolder) {
                $tanggal = basename($dateFolder);

                // 2. Scan folder Event (contoh: 7, 8)
                $eventFolders = File::directories($dateFolder);
                foreach ($eventFolders as $eventFolder) {
                    $eventId = basename($eventFolder);

                    // 3. Cari file video berformat .mp4
                    $mp4Files = File::glob($eventFolder . '/*.mp4');
                    if (empty($mp4Files)) continue;

                    foreach ($mp4Files as $mp4File) {
                        $fileName = basename($mp4File);
                        
                        // Buat format path untuk disimpan ke database: user_X/kamera_Y/tanggal/event/file.mp4
                        $relativePath = "user_{$camera->user_id}/kamera_{$camera->id}/{$tanggal}/{$eventId}/{$fileName}";

                        // 4. Cek apakah video ini sudah ada di database agar tidak duplikat
                        $sudahAda = Recording::where('file_path', $relativePath)->exists();

                        if (!$sudahAda) {
                            $fileSize = File::size($mp4File);
                            // Ambil waktu pembuatan file sebagai start_time
                            $fileTime = File::lastModified($mp4File); 

                            // Simpan ke database
                            Recording::create([
                                'camera_id' => $camera->id,
                                'file_path' => $relativePath,
                                'file_size' => $fileSize,
                                'start_time' => Carbon::createFromTimestamp($fileTime),
                                'record_mode' => $camera->record_mode ?? 'record', 
                                'status' => 'completed',
                            ]);
                            
                            $videoBaru++;
                            $this->line("Ditemukan video baru: " . $relativePath);
                        }
                    }
                }
            }
        }

        $this->info("Sinkronisasi selesai! {$videoBaru} video baru berhasil ditambahkan ke database.");
    }
}