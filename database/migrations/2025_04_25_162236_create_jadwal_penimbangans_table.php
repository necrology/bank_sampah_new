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
        Schema::create('jadwal_penimbangan', function (Blueprint $table) {
            $table->integer('id_jadwal', true);
            $table->timestamp('tanggal')->useCurrentOnUpdate()->useCurrent();
            $table->string('lokasi', 100);
            $table->integer('id_nasabah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_penimbangan');
    }
};