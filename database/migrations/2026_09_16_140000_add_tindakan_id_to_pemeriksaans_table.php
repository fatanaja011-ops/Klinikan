<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaans', function (Blueprint $table) {
            $table->foreignId('tindakan_id')->nullable()->after('dokter_id')->constrained('tindakans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaans', function (Blueprint $table) {
            $table->dropForeign(['tindakan_id']);
            $table->dropColumn('tindakan_id');
        });
    }
};