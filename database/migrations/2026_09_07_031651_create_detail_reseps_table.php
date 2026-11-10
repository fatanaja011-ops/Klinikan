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
        Schema::create('detail_reseps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekam_medis_id')->constrained('rekam_medis')->onDelete('cascade');
            $table->foreignId('obat_id')->constrained('obats')->onDelete('cascade');
            $table->integer('jumlah');
            $table->string('aturan_pakai'); // Contoh: 3x1 sesudah makan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_reseps');
    }
};
