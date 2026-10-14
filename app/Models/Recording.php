<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recording extends Model
{
    /**
     * Tentukan kolom mana saja yang diizinkan untuk diisi data (Mass Assignment).
     */
    protected $fillable = [
        'camera_id',
        'file_path',
        'file_size',
        'start_time',
        'end_time',
        'duration',
        'record_mode', // <--- Tambahan baru untuk support modect, mocord, dll
        'status',
    ];

    /**
     * Relasi: Recording dimiliki oleh satu kamera.
     */
    public function camera(): BelongsTo
    {
        return $this->belongsTo(Camera::class);
    }
}