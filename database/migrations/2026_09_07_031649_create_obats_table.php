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
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_obat')->unique();
            $table->string('nama_obat');
            $table->string('jenis_obat');
            $table->string('satuan');
            $table->decimal('harga', 12, 2);
            $table->integer('stok');
            $table->date('tanggal_kedaluwarsa');
            $table->enum('status', ['Tersedia', 'Habis'])->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
