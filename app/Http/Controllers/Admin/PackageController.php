<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\User;

/**
 * ===========================================================
 * PackageController
 * -----------------------------------------------------------
 * Controller ini digunakan oleh Admin untuk mengelola
 * paket layanan Cloud CCTV.
 *
 * Fitur:
 * - Melihat daftar paket
 * - Menambah paket
 * - Mengubah paket
 * - Menghapus paket
 * ===========================================================
 */
class PackageController extends Controller
{
    /**
     * ===========================================================
     * Menampilkan seluruh data paket layanan.
     *
     * Proses:
     * 1. Mengambil seluruh data paket dari database.
     * 2. Mengurutkan berdasarkan ID.
     * 3. Mengirim data ke halaman daftar paket.
     * ===========================================================
     */
    public function index()
    {
        $packages = Package::orderBy('id')->get();

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * ===========================================================
     * Menampilkan halaman tambah paket.
     * ===========================================================
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * ===========================================================
     * Menyimpan paket layanan baru.
     *
     * Proses:
     * 1. Validasi input.
     * 2. Menyimpan data paket ke database.
     * 3. Kembali ke halaman daftar paket.
     * ===========================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|unique:packages|max:100',
            'storage_limit_gb' => 'required|integer|min:0',
            'camera_limit'     => 'required|integer|min:0',
            'is_custom'        => 'required',
            'status'           => 'required',
        ]);

        Package::create([
            'name'             => $request->name,
            'storage_limit_gb' => $request->storage_limit_gb,
            'camera_limit'     => $request->camera_limit,
            'is_custom'        => $request->is_custom,
            'status'           => $request->status,
        ]);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    /**
     * ===========================================================
     * Halaman detail paket tidak digunakan.
     * ===========================================================
     */
    public function show(Package $package)
    {
        abort(404);
    }

    /**
     * ===========================================================
     * Menampilkan halaman edit paket.
     * ===========================================================
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * ===========================================================
     * Memperbarui data paket.
     *
     * Proses:
     * 1. Validasi input.
     * 2. Memperbarui data paket.
     * 3. Kembali ke halaman daftar paket.
     * ===========================================================
     */
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name'             => 'required|max:100|unique:packages,name,' . $package->id,
            'storage_limit_gb' => 'required|integer|min:0',
            'camera_limit'     => 'required|integer|min:0',
            'is_custom'        => 'required',
            'status'           => 'required',
        ]);

        $package->update([
            'name'             => $request->name,
            'storage_limit_gb' => $request->storage_limit_gb,
            'camera_limit'     => $request->camera_limit,
            'is_custom'        => $request->is_custom,
            'status'           => $request->status,
        ]);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    /**
     * ===========================================================
     * Menghapus paket layanan.
     *
     * Catatan:
     * Paket tidak boleh dihapus apabila masih digunakan user.
     * ===========================================================
     */
    public function destroy(Package $package)
    {
        // Hitung jumlah user yang menggunakan paket ini
        $userCount = User::where('package_id', $package->id)->count();

        // Jika masih digunakan, batalkan proses hapus
        if ($userCount > 0) {

            return redirect()
                ->route('admin.packages.index')
                ->with(
                    'error',
                    'Paket sedang digunakan oleh ' . $userCount . ' user.'
                );
        }

        // Hapus paket
        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
}