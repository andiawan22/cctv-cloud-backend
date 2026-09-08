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
        Schema::create('packages', function (Blueprint $table) {

            $table->id();

            // Nama Paket
            $table->string('name',50)->unique();

            // Kuota Paket
            $table->unsignedInteger('storage_limit_gb');
            $table->unsignedInteger('camera_limit');

            // Apakah paket custom
            $table->boolean('is_custom')->default(false);

            // Status Paket
            $table->boolean('status')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};