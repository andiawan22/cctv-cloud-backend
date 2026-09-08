<?php
/**
 * ===========================================================
 * UserController
 * -----------------------------------------------------------
 * Controller ini digunakan oleh Admin untuk mengelola data
 * seluruh user pada sistem Cloud CCTV.
 *
 * Fitur:
 * - Melihat daftar user
 * - Menambah user
 * - Mengubah data user
 * - Menghapus user
 * ===========================================================
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
use App\Models\StorageUsage;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan seluruh data user.
    
        * 1. Mengambil data user.
        * 2. Mengambil data paket masing-masing user.
        * 3. Mengirim data ke halaman daftar user.
     */
    public function index()
    {
        $users = User::with('package')
            ->orderBy('id')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * supaya halaman Tambah User mendapatkan daftar paket dari database.
     */
    public function create()
    {
        $packages = Package::where('status', true)->get();

        return view('admin.users.create', compact('packages'));
    }

    /**
     * server yang akan mengambil informasi paket tersebut dari tabel packages.
     *  Menyimpan data user baru.
     * 
        * Proses:
        * 1. Validasi data input.
        * 2. Enkripsi password.
        * 3. Menyimpan user ke database.
        * 4. Kembali ke halaman daftar user.
     */
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|max:255',
        'username' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'package_id' => 'required|exists:packages,id',
        'status' => 'required|in:active,inactive',
    ]);

    $user = User::create([

        'package_id' => $request->package_id,

        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,

        'password' => Hash::make($request->password),

        'role' => 'user',
        'status' => $request->status,

    ]);

    StorageUsage::create([

        'user_id' => $user->id,

        'used_storage_gb' => 0,

        'remaining_storage_gb' => $user->package->storage_limit_gb,

        'last_updated' => now(),

    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User berhasil ditambahkan.');
}
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $packages = Package::where('status', true)->get();

        return view('admin.users.edit', compact('user','packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
{
    $request->validate([
    'name' => 'required|max:255',
    'username' => 'required|unique:users,username,' . $user->id,
    'email' => 'required|email|unique:users,email,' . $user->id,
    'package_id' => 'required|exists:packages,id',
    'status' => 'required|in:active,inactive',
]);

    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->package_id = $request->package_id;
    $user->status = $request->status;
    $user->save();

    if ($user->storageUsage) {

    $remaining = max(
        0,
        $user->package->storage_limit_gb - $user->storageUsage->used_storage_gb
    );

    $user->storageUsage->update([

        'remaining_storage_gb' => $remaining,

        'last_updated' => now(),

    ]);

}

    return redirect()
    ->route('admin.users.index')
    ->with('success', 'User berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menghapus akun sendiri
        if ($user->id == auth()->id()) {
           return redirect()
            ->route('admin.users.index')
            ->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}