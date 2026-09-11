<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Camera;

class CameraController extends Controller
{
    public function index()
    {
        $cameras = Auth::user()->cameras()->orderBy('id')->get();
        return view('user.cameras.index', compact('cameras'));
    }

    public function create()
    {
        return view('user.cameras.create');
    }

    public function syncStatus()
    {
        $authData = $this->loginZoneMinder();
        
        if (!$authData) {
            return redirect()->back()->withErrors(['error' => 'Gagal terhubung ke API ZoneMinder untuk sinkronisasi.']);
        }

        $token = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        $url = env('ZONEMINDER_URL') . '/monitors.json' . ($token ? '?token=' . $token : '');

        $response = \Illuminate\Support\Facades\Http::get($url);

        if ($response->successful()) {
            $monitors = $response->json('monitors') ?? [];
            
            $zmStatuses = [];
            foreach ($monitors as $m) {
                $id = $m['Monitor']['Id'];
                
                // Ambil status daemon asli dari ZoneMinder
                $daemonStatus = strtolower($m['Monitor_Status']['Status'] ?? 'offline');
                
                // [PERBAIKAN BUG] Ambil nilai FPS (Frame Per Second) dan ubah jadi angka desimal
                $fps = (float) str_replace(',', '.', $m['Monitor_Status']['CaptureFPS'] ?? '0');

                // Kamera dianggap ONLINE hanya jika daemon berjalan DAN FPS lebih dari 0
                if (($daemonStatus === 'connected' || $daemonStatus === 'running') && $fps > 0) {
                    $zmStatuses[$id] = 'online';
                } else {
                    $zmStatuses[$id] = 'offline';
                }
            }

            $cameras = \Illuminate\Support\Facades\Auth::user()->cameras()->whereNotNull('zoneminder_monitor_id')->get();
            
            foreach ($cameras as $camera) {
                $newStatus = $zmStatuses[$camera->zoneminder_monitor_id] ?? 'offline';
                
                if ($camera->status !== $newStatus) {
                    $camera->update(['status' => $newStatus]);
                }
            }

            return redirect()->route('user.cameras.index')->with('success', ' Status seluruh kamera Cctv berhasil di Refresh');
        }

        return redirect()->back()->withErrors(['error' => 'Gagal mendapatkan data status dari ZoneMinder.']);
    }

    /**
     * ===========================================================
     * Menyimpan kamera baru dan Sinkronisasi ke ZoneMinder.
     * ===========================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'camera_name' => 'required|string|max:255',
            'connection_type' => 'required|in:ONVIF,RTSP',
            // ... validasi lain yang sudah ada ...
            'record_mode' => 'required|string|in:Monitor,Modect,Record,Mocord',
            'resolution' => 'required|string|in:1920x1080,1280x720,640x480',
        ]);

        $user = Auth::user();
        $cameraLimit = $user->package->camera_limit;

        if ($user->cameras()->count() >= $cameraLimit) {
            return redirect()->back()->withInput()->withErrors(['camera_limit' => 'Jumlah kamera telah mencapai batas paket.']);
        }

        $rtspUrl = $request->rtsp_url;

        // Validasi dan Resolusi berdasarkan Tipe Koneksi
        if ($request->connection_type === 'RTSP') {
            $request->validate(['rtsp_url' => 'required']);
        } else {
            $request->validate([
                'ip_address'      => 'required',
                'camera_username' => 'required',
                'camera_password' => 'required',
            ]);

            // [FITUR BARU] Tarik URL RTSP Otomatis dari Kamera ONVIF
            $rtspUrl = $this->resolveOnvifRtspUrl($request->ip_address, $request->camera_username, $request->camera_password);
            
            if (!$rtspUrl) {
                return redirect()->back()->withInput()->withErrors([
                    'camera' => 'Gagal menarik data ONVIF. Pastikan IP, Username, dan Password benar, serta fitur ONVIF di kamera sudah aktif.'
                ]);
            }
        }

        // Simpan ke Database
        $camera = Camera::create([
            'user_id'         => Auth::id(),
            'camera_name'     => $request->camera_name,
            'connection_type' => $request->connection_type,
            'ip_address'      => $request->ip_address,
            'rtsp_url'        => $rtspUrl,
            'camera_username' => $request->camera_username,
            'camera_password' => $request->camera_password,
            'record_mode'     => $request->record_mode, // TAMBAHKAN INI
            'resolution'      => $request->resolution,  // TAMBAHKAN INI
            'status'          => 'offline',
        ]);

        // Integrasi ZoneMinder
        $authData = $this->loginZoneMinder();
        $monitorId = $authData ? $this->createZoneMinderMonitor($camera, $authData) : null;

        if (!$monitorId) {
            $camera->delete();
            return redirect()->back()->withInput()->withErrors(['camera' => 'Kamera gagal ditambahkan ke ZoneMinder.']);
        }

        $camera->update(['zoneminder_monitor_id' => $monitorId]);
        return redirect()->route('user.cameras.index')->with('success', 'Kamera berhasil ditambahkan!');
    }

    public function show(Camera $camera) { abort(404); }

    public function edit(Camera $camera)
    {
        if ($camera->user_id != Auth::id()) abort(403);
        return view('user.cameras.edit', compact('camera'));
    }

    public function update(Request $request, Camera $camera)
    {
        if ($camera->user_id != Auth::id()) abort(403);

        $request->validate([
            'camera_name' => 'required|string|max:255',
            'connection_type' => 'required|in:ONVIF,RTSP',
            
            'record_mode' => 'required|string|in:Monitor,Modect,Record,Mocord',
            'resolution' => 'required|string|in:1920x1080,1280x720,640x480',
        ]);

        $rtspUrl = $request->rtsp_url;

        if ($request->connection_type == 'RTSP') {
            $request->validate(['rtsp_url' => 'required']);
        } else {
            $request->validate([
                'ip_address'      => 'required',
                'camera_username' => 'required',
                'camera_password' => 'required',
            ]);

            // Jika IP/User/Pass berubah, tarik ulang RTSP URL dari kamera ONVIF
            $rtspUrl = $this->resolveOnvifRtspUrl($request->ip_address, $request->camera_username, $request->camera_password);
            if (!$rtspUrl) {
                return redirect()->back()->withInput()->withErrors(['camera' => 'Gagal mengontak ulang kamera ONVIF.']);
            }
        }

        $camera->update([
            'camera_name'     => $request->camera_name,
            'connection_type' => $request->connection_type,
            'ip_address'      => $request->ip_address,
            'rtsp_url'        => $rtspUrl,
            'camera_username' => $request->camera_username,
            'camera_password' => $request->camera_password,
            'record_mode'     => $request->record_mode, // TAMBAHKAN INI
            'resolution'      => $request->resolution,  // TAMBAHKAN INI
        ]);

        if ($camera->zoneminder_monitor_id) {
            $authData = $this->loginZoneMinder();
            if ($authData) $this->updateZoneMinderMonitor($camera->zoneminder_monitor_id, $camera, $authData);
        }

        return redirect()->route('user.cameras.index')->with('success', 'Kamera berhasil diperbarui.');
    }

    public function destroy(Camera $camera)
    {
        if ($camera->user_id != Auth::id()) abort(403);

        if ($camera->zoneminder_monitor_id) {
            $authData = $this->loginZoneMinder();
            if ($authData) $this->deleteZoneMinderMonitor($camera->zoneminder_monitor_id, $authData);
        }

        $camera->delete();
        return redirect()->route('user.cameras.index')->with('success', 'Kamera berhasil dihapus.');
    }

    /**
     * ===========================================================
     * FITUR LIVE VIEW (GRID & SINGLE)
     * ===========================================================
     */
    public function liveGrid()
    {
        $cameras = \Illuminate\Support\Facades\Auth::user()->cameras()->whereNotNull('zoneminder_monitor_id')->get();
        
        $authData = $this->loginZoneMinder();
        $zmToken = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        
        // Memaksa mengambil IP murni saja (menghilangkan /api atau /index.php dari .env)
        $parsed = parse_url(env('ZONEMINDER_URL'));
        $zmHost = $parsed['scheme'] . '://' . $parsed['host'];
        if(isset($parsed['port'])) $zmHost .= ':' . $parsed['port'];
        
        $zmUser = env('ZONEMINDER_USERNAME');
        $zmPass = env('ZONEMINDER_PASSWORD');

        return view('user.cameras.live_grid', compact('cameras', 'zmToken', 'zmHost', 'zmUser', 'zmPass'));
    }

    public function liveSingle(\App\Models\Camera $camera)
    {
        if ($camera->user_id != \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        $authData = $this->loginZoneMinder();
        $zmToken = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        
        // Memaksa mengambil IP murni saja 
        $parsed = parse_url(env('ZONEMINDER_URL'));
        $zmHost = $parsed['scheme'] . '://' . $parsed['host'];
        if(isset($parsed['port'])) $zmHost .= ':' . $parsed['port'];
        
        $zmUser = env('ZONEMINDER_USERNAME');
        $zmPass = env('ZONEMINDER_PASSWORD');

        return view('user.cameras.live_single', compact('camera', 'zmToken', 'zmHost', 'zmUser', 'zmPass'));
    }

    /**
     * ===========================================================
     * FUNGSI KEAJAIBAN ONVIF (PROBE OTOMATIS)
     * ===========================================================
     */
    
    // 1. Membuat Kode Keamanan SOAP (WS-Security)
    private function getOnvifAuthHeader($username, $password)
    {
        $nonce = random_bytes(16);
        $timestamp = gmdate('Y-m-d\TH:i:s.000\Z');
        $passwordDigest = base64_encode(sha1($nonce . $timestamp . $password, true));
        $b64Nonce = base64_encode($nonce);

        return '
        <s:Header>
            <Security s:mustUnderstand="1" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                <UsernameToken>
                    <Username>'.$username.'</Username>
                    <Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">'.$passwordDigest.'</Password>
                    <Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">'.$b64Nonce.'</Nonce>
                    <Created xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">'.$timestamp.'</Created>
                </UsernameToken>
            </Security>
        </s:Header>';
    }

    // 2. Mengekstrak RTSP URL dari Kamera
    private function resolveOnvifRtspUrl($ip, $username, $password)
    {
        $authHeader = $this->getOnvifAuthHeader($username, $password);
        $url = "http://{$ip}/onvif/device_service";
        $headers = ['Content-Type' => 'application/soap+xml; charset=utf-8'];

        // Tahap A: Minta Daftar Profil Video
        $getProfilesXml = '<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope">' . $authHeader . '<s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><GetProfiles xmlns="http://www.onvif.org/ver10/media/wsdl"/></s:Body></s:Envelope>';
        
        try {
            $response = Http::timeout(5)->withHeaders($headers)->post($url, $getProfilesXml);
            if (!preg_match('/token="([^"]+)"/', $response->body(), $matches)) return null;
            $profileToken = $matches[1];

            // Tahap B: Minta URL RTSP dari Profil yang Didapat
            $getUriXml = '<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope">' . $authHeader . '<s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><GetStreamUri xmlns="http://www.onvif.org/ver10/media/wsdl"><StreamSetup><Stream xmlns="http://www.onvif.org/ver10/schema">RTP-Unicast</Stream><Transport xmlns="http://www.onvif.org/ver10/schema"><Protocol>RTSP</Protocol></Transport></StreamSetup><ProfileToken>'.$profileToken.'</ProfileToken></GetStreamUri></s:Body></s:Envelope>';
            
            $responseUri = Http::timeout(5)->withHeaders($headers)->post($url, $getUriXml);
            if (!preg_match('/>([^<]+rtsp:\/\/[^<]+)</i', $responseUri->body(), $uriMatches)) return null;
            
            // Tahap C: Inject Username dan Password ke URL agar ZoneMinder punya akses
            $rawUrl = $uriMatches[1];
            $parsed = parse_url($rawUrl);
            
            $finalRtsp = "rtsp://{$username}:{$password}@{$ip}";
            if(isset($parsed['port'])) $finalRtsp .= ":{$parsed['port']}";
            if(isset($parsed['path'])) $finalRtsp .= $parsed['path'];
            if(isset($parsed['query'])) $finalRtsp .= "?{$parsed['query']}";

            return $finalRtsp;

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * ===========================================================
     * API ZONEMINDER UTILITIES
     * ===========================================================
     */
    private function loginZoneMinder() {
        $response = Http::asForm()->post(env('ZONEMINDER_URL') . '/host/login.json', [
            'user' => env('ZONEMINDER_USERNAME'),
            'pass' => env('ZONEMINDER_PASSWORD'),
        ]);
        return $response->successful() ? $response->json() : null;
    }

    private function createZoneMinderMonitor($camera, $authData)
    {
        $token = $authData['access_token'] ?? ($authData['credentials'] ?? '');

        // Menangkap input dinamis, memberikan nilai default jika kosong
        // (Pastikan nama variabel ini sesuai dengan nama kolom di database/form kamu)
        $width = $camera->width ?? 1280; 
        $height = $camera->height ?? 720;
        $function = $camera->recording_method ?? 'Monitor'; // Misal: Monitor, Modect, Record

        $monitorData = [
            'Monitor' => [
                'Name' => $camera->camera_name,
                'Type' => 'Ffmpeg',
                'Function' => $function,
                'Enabled' => 1,
                'Method' => 'rtpRtsp',
                'Path' => $camera->rtsp_url,
                'Width' => $width, 
                'Height' => $height, 
                'Colours' => 3,
            ]
        ];

        $url = env('ZONEMINDER_URL') . '/monitors.json';
        if ($token) { $url .= '?token=' . $token; }

        $response = Http::post($url, $monitorData);

        // Jika berhasil disimpan di ZM, ambil ID Monitor-nya untuk disimpan di database Laravel
        if ($response->successful() && $response->json('message') === 'Saved') {
            return $this->getMonitorIdByName($camera->camera_name, $token);
        }

        return null;
    }

    private function getMonitorIdByName($cameraName, $token)
    {
        $url = env('ZONEMINDER_URL') . '/monitors.json';
        if ($token) { $url .= '?token=' . $token; }

        $response = Http::get($url);

        if ($response->successful()) {
            $monitors = $response->json('monitors') ?? [];
            foreach ($monitors as $monitor) {
                if (($monitor['Monitor']['Name'] ?? '') === $cameraName) {
                    return $monitor['Monitor']['Id'];
                }
            }
        }
        return null;
    }

    private function updateZoneMinderMonitor($monitorId, $camera, $authData) {
        $token = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        $url = env('ZONEMINDER_URL') . '/monitors/' . $monitorId . '.json' . ($token ? '?token=' . $token : '');
        
        $dimensi = explode('x', $camera->resolution);
        $width = $dimensi[0] ?? 1280;
        $height = $dimensi[1] ?? 720;

        $monitorData = [
            'Monitor' => [
                'Name' => $camera->camera_name, 
                'Path' => $camera->rtsp_url,
                'Function' => $camera->record_mode,
                'Width' => $width,
                'Height' => $height,
            ]
        ];
        
        return Http::put($url, $monitorData)->successful();
    }

    private function deleteZoneMinderMonitor($monitorId, $authData) {
        $token = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        $url = env('ZONEMINDER_URL') . '/monitors/' . $monitorId . '.json' . ($token ? '?token=' . $token : '');
        return Http::delete($url)->successful();
    }
    public function playbackIndex()
    {
        // Ambil daftar kamera milik user yang sedang login
        $cameras = Camera::where('user_id', auth()->id())->get();
        
        // Tampilkan halaman form playback
        return view('user.playback.index', compact('cameras'));
    }
    public function playbackShow(Request $request)
    {
        $request->validate([
            'camera_id' => 'required',
            'tanggal' => 'required|date',
        ]);

        $userId = auth()->id();
        $cameraId = $request->camera_id;
        $tanggal = $request->tanggal;

        $folderName = 'user_' . $userId;
        $kameraFolder = 'kamera_' . $cameraId;
        $targetPath = storage_path("recordings/{$folderName}/{$kameraFolder}/{$tanggal}");

        // Cek apakah folder di tanggal tersebut ada datanya
        if (file_exists($targetPath)) {
            // Ambil daftar event (angka-angka tadi), lalu urutkan
            $eventFolders = array_diff(scandir($targetPath), array('.', '..'));
            sort($eventFolders); 

            // Ambil data kamera untuk ditampilkan namanya
            $kamera = \App\Models\Camera::find($cameraId);

            // Arahkan ke halaman pemutar video
            return view('user.playback.show', compact('kamera', 'tanggal', 'eventFolders'));
        } else {
            return back()->with('error', 'Rekaman pada tanggal ' . $tanggal . ' tidak ditemukan.');
        }
    }

    // Fungsi untuk membuka halaman pemutar video
    public function playbackPlay($cameraId, $tanggal, $eventId)
    {
        $kamera = \App\Models\Camera::findOrFail($cameraId);
        $userId = auth()->id();
        
        // Cek folder event tersebut
        $path = storage_path("recordings/user_{$userId}/kamera_{$cameraId}/{$tanggal}/{$eventId}");
        
        // Cari file video berformat .mp4 di dalam folder tersebut
        $files = glob($path . '/*.mp4');
        $videoFile = count($files) > 0 ? basename($files[0]) : null;

        return view('user.playback.play', compact('kamera', 'tanggal', 'eventId', 'videoFile'));
    }

    // Fungsi "Pipa" untuk mengalirkan file video fisik ke website (streaming)
    public function streamVideo(Request $request)
    {
        $userId = auth()->id();
        $path = storage_path("recordings/user_{$userId}/kamera_{$request->camera}/{$request->tanggal}/{$request->event}/{$request->file}");
        
        if(file_exists($path)) {
            // Gunakan fitur bawaan Laravel untuk menampilkan file
            return response()->file($path);
        }
        
        abort(404, 'File video tidak ditemukan');
    }
}