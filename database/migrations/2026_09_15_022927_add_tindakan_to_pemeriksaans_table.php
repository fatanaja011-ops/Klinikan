<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaans', function (Blueprint $table) {
            $table->text('tindakan')->nullable()->after('diagnosis');
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaans', function (Blueprint $table) {
            $table->dropColumn('tindakan');
        });
    }
};