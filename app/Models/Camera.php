<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    /**
     * Field yang boleh diisi
     */
    protected $fillable = [

    'user_id',
    'camera_name',
    'connection_type',
    'ip_address',
    'onvif_port',
    'camera_username',
    'camera_password',
    'rtsp_url',
    'status',
    'zoneminder_monitor_id',
    'record_mode',           
    'resolution',           

];

    /**
     * Kamera dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}