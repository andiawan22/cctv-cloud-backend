<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cameras', function (Blueprint $table) {

            $table->unsignedBigInteger('zoneminder_monitor_id')
                  ->nullable()
                  ->after('status');

        });
    }

    public function down(): void
    {
        Schema::table('cameras', function (Blueprint $table) {

            $table->dropColumn('zoneminder_monitor_id');

        });
    }
};