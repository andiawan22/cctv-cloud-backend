<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel storage_usage
     */
    public function up(): void
    {
        Schema::create('storage_usage', function (Blueprint $table) {

            $table->id();

            // Relasi ke user
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            // Storage yang sudah digunakan (GB)
            $table->decimal('used_storage_gb', 10, 2)
                ->default(0);

            // Storage yang masih tersisa (GB)
            $table->decimal('remaining_storage_gb', 10, 2)
                ->default(0);

            // Terakhir diperbarui
            $table->timestamp('last_updated')
                ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Menghapus tabel storage_usage
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_usage');
    }
};