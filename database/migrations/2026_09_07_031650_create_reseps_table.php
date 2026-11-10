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
        Schema::create('reseps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaans')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('dokters')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->date('tgl_resep');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
