<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antreans', function (Blueprint $table) {
            $table->enum('status', ['Menunggu', 'Dipanggil', 'Diperiksa', 'Selesai', 'Batal'])
                ->default('Menunggu')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('antreans', function (Blueprint $table) {
            $table->enum('status', ['Menunggu', 'Dipanggil', 'Selesai', 'Batal'])
                ->default('Menunggu')
                ->change();
        });
    }
};