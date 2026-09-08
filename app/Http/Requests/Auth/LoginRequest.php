<?php

/**
 * ==========================================================
 * Sistem      : Cloud CCTV Berbasis Laravel
 * Framework   : Laravel 13
 * File        : LoginRequest.php
 * Lokasi      : app/Http/Requests/Auth/
 * Fungsi      : Mengatur proses autentikasi (login) pengguna
 * ==========================================================
 */

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan mengakses request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi input login.
     *  * Username dan password wajib diisi.
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Proses autentikasi (login) pengguna.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Memastikan pengguna belum melewati batas percobaan login
        $this->ensureIsNotRateLimited();

        // Data yang digunakan untuk proses login
        $credentials = [
            'username' => $this->input('username'),
            'password' => $this->input('password'),
            'status'   => 'active', // Hanya akun aktif yang dapat login
        ];

        // Jika login gagal
        if (! Auth::attempt($credentials, $this->boolean('remember'))) {

            // Menambah jumlah percobaan login
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => 'Username, password salah, atau akun belum aktif.',
            ]);
        }

        // Menghapus jumlah percobaan login jika berhasil
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Memastikan pengguna tidak melebihi batas percobaan login.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // Mencatat bahwa pengguna terkena lockout
        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Membuat kunci unik untuk Rate Limiter
     * berdasarkan username dan alamat IP.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('username')) . '|' . $this->ip()
        );
    }
}