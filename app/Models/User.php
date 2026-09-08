<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Field yang boleh diisi (Mass Assignment)
     */
    protected $fillable = [
        'package_id',
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    #[Hidden([
        'password',
        'remember_token',
    ])]

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke paket layanan.
     * Satu user hanya memiliki satu paket.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Relasi ke kamera.
     * Satu user dapat memiliki banyak kamera.
     */
    public function cameras(): HasMany
    {
        return $this->hasMany(Camera::class);
    }

    /**
     * Relasi ke penggunaan storage.
     * Satu user memiliki satu data storage.
     */
    public function storageUsage(): HasOne
    {
        return $this->hasOne(StorageUsage::class);
    }
}