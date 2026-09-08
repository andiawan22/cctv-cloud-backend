<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Camera;
use App\Models\User;

/**
 * ===========================================================
 * CameraController (ADMIN)
 * -----------------------------------------------------------
 * Controller ini digunakan oleh Admin untuk mengelola kamera
 * seluruh user pada sistem Cloud CCTV.
 * ===========================================================
 */
class CameraController extends Controller
{
    /**
     * ===========================================================
     * Menampilkan seluruh data kamera.
     * ===========================================================
     */
    public function index()
    {
        $cameras = Camera::with('user')
            ->orderBy('id')
            ->get();

        return view('admin.cameras.index', compact('cameras'));
    }

    /**
     * ===========================================================
     * Menampilkan halaman tambah kamera.
     * ===========================================================
     */
    public function create()
    {
        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.cameras.create', compact('users'));
    }

    /**
     * ===========================================================
     * Menyimpan kamera baru dan Sinkronisasi ke ZoneMinder.
     * ===========================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'camera_name'     => 'required|max:100',
            'connection_type' => 'required|in:RTSP,ONVIF',
        ]);

        $rtspUrl = $request->rtsp_url;

        if ($request->connection_type === 'RTSP') {
            $request->validate([
                'rtsp_url' => 'required',
            ]);
        } else {
            $request->validate([
                'ip_address'        => 'required',
                'camera_username'   => 'required',
                'camera_password'   => 'required',
            ]);

            // Tarik URL RTSP Otomatis dari Kamera ONVIF
            $rtspUrl = $this->resolveOnvifRtspUrl($request->ip_address, $request->camera_username, $request->camera_password);
            
            if (!$rtspUrl) {
                return redirect()->back()->withInput()->withErrors([
                    'camera' => 'Gagal menarik data ONVIF dari IP Camera. Pastikan IP, Username, Password benar, serta fitur ONVIF aktif.'
                ]);
            }
        }

        // 1. Simpan Kamera Sementara (Status Offline)
        $camera = Camera::create([
            'user_id'           => $request->user_id,
            'camera_name'       => $request->camera_name,
            'connection_type'   => $request->connection_type,
            'ip_address'        => $request->ip_address,
            'rtsp_url'          => $rtspUrl, // Simpan hasil RTSP URL
            'camera_username'   => $request->camera_username,
            'camera_password'   => $request->camera_password,
            'status'            => 'offline',
        ]);

        // 2. Integrasi API ZoneMinder
        $authData = $this->loginZoneMinder();
        $monitorId = $authData ? $this->createZoneMinderMonitor($camera, $authData) : null;

        // 3. Evaluasi Hasil (Rollback jika gagal)
        if (!$monitorId) {
            $camera->delete(); 
            return redirect()->back()->withInput()->withErrors([
                'camera' => 'Kamera gagal ditambahkan ke ZoneMinder. Pastikan URL RTSP valid dan API aktif.'
            ]);
        }

        // 4. Update ID ZoneMinder
        $camera->update(['zoneminder_monitor_id' => $monitorId]);

        return redirect()->route('admin.cameras.index')->with('success', 'Kamera user berhasil ditambahkan.');
    }

    /**
     * ===========================================================
     * Halaman detail kamera tidak digunakan.
     * ===========================================================
     */
    public function show(Camera $camera)
    {
        abort(404);
    }

    /**
     * ===========================================================
     * Menampilkan halaman edit kamera.
     * ===========================================================
     */
    public function edit(Camera $camera)
    {
        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.cameras.edit', compact('camera', 'users'));
    }

    /**
     * ===========================================================
     * Memperbarui data kamera di Laravel dan ZoneMinder.
     * ===========================================================
     */
    public function update(Request $request, Camera $camera)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'camera_name'     => 'required|max:100',
            'connection_type' => 'required|in:RTSP,ONVIF',
        ]);

        $rtspUrl = $request->rtsp_url;

        if ($request->connection_type === 'RTSP') {
            $request->validate(['rtsp_url' => 'required']);
        } else {
            $request->validate([
                'ip_address'      => 'required',
                'camera_username' => 'required',
                'camera_password' => 'required',
            ]);

            // Jika ONVIF, perbarui URL-nya
            $rtspUrl = $this->resolveOnvifRtspUrl($request->ip_address, $request->camera_username, $request->camera_password);
            
            if (!$rtspUrl) {
                return redirect()->back()->withInput()->withErrors([
                    'camera' => 'Gagal mengontak ulang kamera ONVIF. Pastikan kredensial benar.'
                ]);
            }
        }

        // 1. Update data di database Laravel lokal
        $camera->update([
            'user_id'         => $request->user_id,
            'camera_name'     => $request->camera_name,
            'connection_type' => $request->connection_type,
            'ip_address'      => $request->ip_address,
            'rtsp_url'        => $rtspUrl,
            'camera_username' => $request->camera_username,
            'camera_password' => $request->camera_password,
        ]);

        // 2. Sinkronisasi perubahan ke API ZoneMinder jika kamera sudah tertaut
        if ($camera->zoneminder_monitor_id) {
            $authData = $this->loginZoneMinder();
            
            if ($authData) {
                $this->updateZoneMinderMonitor($camera->zoneminder_monitor_id, $camera, $authData);
            }
        }

        return redirect()->route('admin.cameras.index')->with('success', 'Kamera berhasil diperbarui di Cloud.');
    }

    /**
     * ===========================================================
     * Menghapus kamera dari Laravel dan ZoneMinder.
     * ===========================================================
     */
    public function destroy(Camera $camera)
    {
        $monitorId = $camera->zoneminder_monitor_id;

        if ($monitorId) {
            $authData = $this->loginZoneMinder();
            if ($authData) {
                $this->deleteZoneMinderMonitor($monitorId, $authData);
            }
        }

        $camera->delete();

        return redirect()->route('admin.cameras.index')->with('success', 'Kamera berhasil dihapus dari Cloud dan ZoneMinder.');
    }

    /**
     * ===========================================================
     * Sinkronisasi Status Kamera (Online/Offline) dari ZoneMinder
     * ===========================================================
     */
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
                $daemonStatus = strtolower($m['Monitor_Status']['Status'] ?? 'offline');
                $fps = (float) str_replace(',', '.', $m['Monitor_Status']['CaptureFPS'] ?? '0');

                if (($daemonStatus === 'connected' || $daemonStatus === 'running') && $fps > 0) {
                    $zmStatuses[$id] = 'online';
                } else {
                    $zmStatuses[$id] = 'offline';
                }
            }

            $cameras = Camera::whereNotNull('zoneminder_monitor_id')->get();
            
            foreach ($cameras as $camera) {
                $newStatus = $zmStatuses[$camera->zoneminder_monitor_id] ?? 'offline';
                if ($camera->status !== $newStatus) {
                    $camera->update(['status' => $newStatus]);
                }
            }

            return redirect()->route('admin.cameras.index')->with('success', 'Status seluruh kamera CCTV berhasil di-Refresh.');
        }

        return redirect()->back()->withErrors(['error' => 'Gagal mendapatkan data status dari ZoneMinder.']);
    }

    /**
     * ===========================================================
     * FITUR LIVE VIEW (ADMIN) - Melihat Semua Kamera
     * ===========================================================
     */
    public function liveGrid(Request $request)
    {
        // 1. Ambil daftar user untuk opsi dropdown (hanya user yang punya kamera tertaut)
        $users = \App\Models\User::whereHas('cameras', function($q) {
            $q->whereNotNull('zoneminder_monitor_id');
        })->get();

        // 2. Query dasar kamera
        $query = \App\Models\Camera::with('user')->whereNotNull('zoneminder_monitor_id');

        // 3. Cek apakah ada filter user_id dari URL
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $cameras = $query->get();
        $selectedUser = $request->user_id; // Menyimpan ID user yang sedang dipilih

        // 4. Autentikasi ZoneMinder
        $authData = $this->loginZoneMinder();
        $zmToken = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        
        $parsed = parse_url(env('ZONEMINDER_URL'));
        $zmHost = $parsed['scheme'] . '://' . $parsed['host'];
        if(isset($parsed['port'])) $zmHost .= ':' . $parsed['port'];
        
        $zmUser = env('ZONEMINDER_USERNAME');
        $zmPass = env('ZONEMINDER_PASSWORD');

        return view('admin.cameras.live_grid', compact('cameras', 'users', 'selectedUser', 'zmToken', 'zmHost', 'zmUser', 'zmPass'));
    }

    public function liveSingle(Camera $camera)
    {
        $authData = $this->loginZoneMinder();
        $zmToken = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        
        $parsed = parse_url(env('ZONEMINDER_URL'));
        $zmHost = $parsed['scheme'] . '://' . $parsed['host'];
        if(isset($parsed['port'])) $zmHost .= ':' . $parsed['port'];
        
        $zmUser = env('ZONEMINDER_USERNAME');
        $zmPass = env('ZONEMINDER_PASSWORD');

        return view('admin.cameras.live_single', compact('camera', 'zmToken', 'zmHost', 'zmUser', 'zmPass'));
    }

    /**
     * ===========================================================
     * PRIVATE METHODS UNTUK API ZONEMINDER
     * ===========================================================
     */
    private function loginZoneMinder()
    {
        $response = Http::asForm()->post(env('ZONEMINDER_URL') . '/host/login.json', [
            'user' => env('ZONEMINDER_USERNAME'),
            'pass' => env('ZONEMINDER_PASSWORD'),
        ]);

        return $response->successful() ? $response->json() : null;
    }

    private function createZoneMinderMonitor($camera, $authData)
    {
        $token = $authData['access_token'] ?? ($authData['credentials'] ?? '');

        $monitorData = [
            'Monitor' => [
                'Name' => $camera->camera_name,
                'Type' => 'Ffmpeg',
                'Function' => 'Monitor',
                'Enabled' => 1,
                'Method' => 'rtpRtsp',
                'Path' => $camera->rtsp_url,
                'Width' => 1280, 
                'Height' => 720, 
                'Colours' => 3,
            ]
        ];

        $url = env('ZONEMINDER_URL') . '/monitors.json';
        if ($token) { $url .= '?token=' . $token; }

        $response = Http::post($url, $monitorData);

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

    private function deleteZoneMinderMonitor($monitorId, $authData)
    {
        $token = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        $url = env('ZONEMINDER_URL') . '/monitors/' . $monitorId . '.json';
        
        if ($token) { $url .= '?token=' . $token; }

        $response = Http::delete($url);
        return $response->successful();
    }

    private function updateZoneMinderMonitor($monitorId, $camera, $authData)
    {
        $token = $authData['access_token'] ?? ($authData['credentials'] ?? '');
        $url = env('ZONEMINDER_URL') . '/monitors/' . $monitorId . '.json';
        
        if ($token) { $url .= '?token=' . $token; }

        $monitorData = [
            'Monitor' => [
                'Name' => $camera->camera_name,
                'Path' => $camera->rtsp_url,
            ]
        ];

        $response = Http::put($url, $monitorData);
        return $response->successful();
    }

    /**
     * ===========================================================
     * FUNGSI ONVIF PROBE (OTOMATISASI RTSP)
     * ===========================================================
     */
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

    private function resolveOnvifRtspUrl($ip, $username, $password)
    {
        $authHeader = $this->getOnvifAuthHeader($username, $password);
        
        // Perbaikan 1: Kita coba tembak ke endpoint standar Media Service ONVIF
        $url = "http://{$ip}/onvif/Media_service"; 
        // Catatan: Jika nanti error 404, kita kembalikan ke /onvif/device_service
        
        $headers = ['Content-Type' => 'application/soap+xml; charset=utf-8'];

        $getProfilesXml = '<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope">' . $authHeader . '<s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><GetProfiles xmlns="http://www.onvif.org/ver10/media/wsdl"/></s:Body></s:Envelope>';
        
        try {
            $response = Http::timeout(5)->withHeaders($headers)->post($url, $getProfilesXml);
            
            // KODE DETEKTIF 1: Cek jika Kamera menolak (Status bukan 200 OK)
            if ($response->failed()) {
                dd([
                    '1_Pesan' => 'Kamera menolak request kita (HTTP Error)',
                    '2_Status_Code' => $response->status(),
                    '3_Jawaban_Kamera' => $response->body(),
                    '4_URL_Target' => $url
                ]);
            }

            // KODE DETEKTIF 2: Cek apakah token berhasil ditangkap Regex
            if (!preg_match('/token="([^"]+)"/', $response->body(), $matches)) {
                dd([
                    '1_Pesan' => 'Koneksi sukses, tapi Token Profile tidak ditemukan di XML',
                    '2_Jawaban_Kamera' => $response->body()
                ]);
            }
            
            $profileToken = $matches[1];

            $getUriXml = '<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope">' . $authHeader . '<s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><GetStreamUri xmlns="http://www.onvif.org/ver10/media/wsdl"><StreamSetup><Stream xmlns="http://www.onvif.org/ver10/schema">RTP-Unicast</Stream><Transport xmlns="http://www.onvif.org/ver10/schema"><Protocol>RTSP</Protocol></Transport></StreamSetup><ProfileToken>'.$profileToken.'</ProfileToken></GetStreamUri></s:Body></s:Envelope>';
            
            $responseUri = Http::timeout(5)->withHeaders($headers)->post($url, $getUriXml);
            if (!preg_match('/>([^<]+rtsp:\/\/[^<]+)</i', $responseUri->body(), $uriMatches)) return null;
            
            $rawUrl = $uriMatches[1];
            $parsed = parse_url($rawUrl);
            
            $finalRtsp = "rtsp://{$username}:{$password}@{$ip}";
            if(isset($parsed['port'])) $finalRtsp .= ":{$parsed['port']}";
            if(isset($parsed['path'])) $finalRtsp .= $parsed['path'];
            if(isset($parsed['query'])) $finalRtsp .= "?{$parsed['query']}";

            return $finalRtsp;
            
        } catch (\Exception $e) {
            // KODE DETEKTIF 3: Jika Timeout atau salah Port
            dd([
                '1_Pesan' => 'Koneksi Terputus / Timeout / Server Tidak Merespon',
                '2_Pesan_Error_Asli' => $e->getMessage(),
                '3_Saran' => 'Coba tambahkan Port di isian IP (Misal: 192.168.1.22:8899 atau 192.168.1.22:80)'
            ]);
        }
    }
}