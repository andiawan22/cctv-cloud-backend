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
        Schema::create('recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camera_id')->constrained('cameras')->cascadeOnDelete();
            $table->string('file_path');
            $table->bigInteger('file_size')->default(0); 
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->integer('duration')->nullable(); 
            $table->string('record_mode')->default('record'); // Support modect, mocord, record
            $table->string('status')->default('completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recordings');
    }
};
