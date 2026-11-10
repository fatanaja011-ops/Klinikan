<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->decimal('nominal_dibayar', 12, 2)->nullable()->after('total_bayar');
            $table->decimal('kembalian', 12, 2)->nullable()->after('nominal_dibayar');
            $table->string('metode_pembayaran')->nullable()->after('status_pembayaran');
            $table->text('snap_token')->nullable()->after('metode_pembayaran');
            $table->string('order_id')->nullable()->unique()->after('snap_token');
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropUnique(['order_id']);
            $table->dropColumn([
                'nominal_dibayar',
                'kembalian',
                'metode_pembayaran',
                'snap_token',
                'order_id',
            ]);
        });
    }
};