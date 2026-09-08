<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name',
        'storage_limit_gb',
        'camera_limit',
        'is_custom',
        'status',
    ];

    /**
     * Satu paket dapat dimiliki banyak user.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}