<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cameras', function (Blueprint $table) {
            // Menambahkan kolom onvif_port setelah ip_address dengan nilai bawaan 8899
            $table->integer('onvif_port')->default(8899)->after('ip_address');
        });
    }

    public function down()
    {
        Schema::table('cameras', function (Blueprint $table) {
            $table->dropColumn('onvif_port');
        });
    }
};