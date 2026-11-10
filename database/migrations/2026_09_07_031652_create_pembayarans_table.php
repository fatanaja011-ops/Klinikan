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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('no_pembayaran')->unique();
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->decimal('total_bayar', 12, 2);
            $table->enum('status_pembayaran', ['Belum Bayar', 'Lunas'])->default('Belum Bayar');
            $table->date('tgl_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
