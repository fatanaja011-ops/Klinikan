<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'dokter')
            ->orderBy('id')
            ->eachById(function (object $user): void {
                if (DB::table('dokters')->where('user_id', $user->id)->exists()) {
                    return;
                }

                $number = DB::table('dokters')->count() + 1;
                do {
                    $kode = 'DOC' . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
                    $number++;
                } while (DB::table('dokters')->where('kode_dokter', $kode)->exists());

                DB::table('dokters')->insert([
                    'user_id' => $user->id,
                    'kode_dokter' => $kode,
                    'nama_dokter' => $user->name,
                    'spesialisasi' => 'Umum',
                    'no_telepon' => '-',
                    'alamat' => '-',
                    'status' => 'aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        // Doctor profiles may contain edits made after this migration.
    }
};