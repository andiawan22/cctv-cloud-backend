<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'camera_id',
    'file_path',
    'file_size',
    'start_time',
    'end_time',
    'duration',
    'status',
])]

class Recording extends Model
{
    /**
     * Recording dimiliki oleh satu kamera.
     */
    public function camera(): BelongsTo
    {
        return $this->belongsTo(Camera::class);
    }
}