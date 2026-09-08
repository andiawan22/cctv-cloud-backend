<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageUsage extends Model
{
    /**
     * Nama tabel
     */
    protected $table = 'storage_usage';

    /**
     * Field yang boleh diisi
     */
    protected $fillable = [
        'user_id',
        'used_storage_gb',
        'remaining_storage_gb',
        'last_updated',
    ];

    /**
     * Satu data storage dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}