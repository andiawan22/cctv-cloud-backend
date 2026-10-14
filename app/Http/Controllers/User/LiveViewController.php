<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LiveViewController extends Controller
{
    private function loginZoneMinder() {
        $response = Http::asForm()->post(env('ZONEMINDER_URL') . '/host/login.json', [
            'user' => env('ZONEMINDER_USERNAME'),
            'pass' => env('ZONEMINDER_PASSWORD'),
        ]);
        return $response->successful() ? $response->json() : null;
    }

    public function liveGrid()
    {
        $cameras = Auth::user()->cameras()->whereNotNull('zoneminder_monitor_id')->get();
        $authData = $this->loginZoneMinder();
        $zmToken = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        
        $parsed = parse_url(env('ZONEMINDER_URL'));
        $zmHost = $parsed['scheme'] . '://' . $parsed['host'];
        if(isset($parsed['port'])) $zmHost .= ':' . $parsed['port'];
        
        $zmUser = env('ZONEMINDER_USERNAME');
        $zmPass = env('ZONEMINDER_PASSWORD');

        return view('user.cameras.live_grid', compact('cameras', 'zmToken', 'zmHost', 'zmUser', 'zmPass'));
    }

    public function liveSingle(Camera $camera)
    {
        if ($camera->user_id != Auth::id()) abort(403);

        $authData = $this->loginZoneMinder();
        $zmToken = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        
        $parsed = parse_url(env('ZONEMINDER_URL'));
        $zmHost = $parsed['scheme'] . '://' . $parsed['host'];
        if(isset($parsed['port'])) $zmHost .= ':' . $parsed['port'];
        
        $zmUser = env('ZONEMINDER_USERNAME');
        $zmPass = env('ZONEMINDER_PASSWORD');

        return view('user.cameras.live_single', compact('camera', 'zmToken', 'zmHost', 'zmUser', 'zmPass'));
    }

    public function controlPtz(\Illuminate\Http\Request $request)
    {
        // 1. Cari data kamera (Pastikan milik user yang sedang login)
        $camera = \App\Models\Camera::where('id', $request->camera_id)
                    ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->firstOrFail();

        // 2. Amankan semua input sebelum dikirim ke terminal Linux (WAJIB)
        $arah = escapeshellarg($request->direction);
        $ip   = escapeshellarg($camera->ip_address);
        $user = escapeshellarg($camera->camera_username ?? 'admin'); // Sesuaikan nama kolom database-mu
        $pass = escapeshellarg($camera->camera_password ?? 'admin');
        $port = escapeshellarg($camera->onvif_port ?? 8899);

        // 3. Lokasi script Python
        $scriptPath = base_path('ptz_control.py');

        // 4. Rakit dan jalankan perintah terminal
        $command = "python3 {$scriptPath} {$ip} {$user} {$pass} {$arah} {$port} 2>&1";
        $output  = shell_exec($command);

        return response()->json([
            'status'  => 'success', 
            'message' => "Perintah $request->direction dikirim",
            'output'  => $output
        ]);
    }
}