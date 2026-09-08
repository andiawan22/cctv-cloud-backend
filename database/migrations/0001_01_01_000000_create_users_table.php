<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel Users
     Schema::create('users', function (Blueprint $table) {

    $table->id();

    // Relasi ke paket
    $table->foreignId('package_id')
          ->constrained('packages')
          ->restrictOnDelete()
          ->cascadeOnUpdate();

    // Informasi User
    $table->string('name',100);

    $table->string('username',50)->unique();

    $table->string('email')->unique();

    $table->timestamp('email_verified_at')->nullable();

    $table->string('password');

    // Hak Akses
    $table->enum('role',['admin','user'])
          ->default('user');

    $table->enum('status',['active','inactive'])
          ->default('active');

    // Laravel
    $table->rememberToken();

    $table->timestamps();

});

        // Tabel Password Reset
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabel Session
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};