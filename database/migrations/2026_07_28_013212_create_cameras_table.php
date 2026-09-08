<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel cameras
     */
    public function up(): void
    {
        Schema::create('cameras', function (Blueprint $table) {

            // Primary Key
            $table->id();

            /*
             |-------------------------------------------------------
             | Kamera dimiliki oleh satu user
             |-------------------------------------------------------
             */
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
             |-------------------------------------------------------
             | Informasi Kamera
             |-------------------------------------------------------
             */
            $table->string('camera_name');

            /*
             |-------------------------------------------------------
             | Jenis koneksi kamera
             | RTSP atau ONVIF
             |-------------------------------------------------------
             */
            $table->enum('connection_type', [
                'RTSP',
                'ONVIF'
            ]);

            /*
             |-------------------------------------------------------
             | Khusus ONVIF
             |-------------------------------------------------------
             */
            $table->string('ip_address')->nullable();

            $table->string('camera_username')->nullable();

            $table->string('camera_password')->nullable();

            /*
             |-------------------------------------------------------
             | Khusus RTSP
             |-------------------------------------------------------
             */
            $table->text('rtsp_url')->nullable();

            /*
             |-------------------------------------------------------
             | Status kamera
             |-------------------------------------------------------
             */
            $table->enum('status', [
                'online',
                'offline'
            ])->default('offline');

            /*
             |-------------------------------------------------------
             | Timestamp
             |-------------------------------------------------------
             */
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel cameras
     */
    public function down(): void
    {
        Schema::dropIfExists('cameras');
    }
};