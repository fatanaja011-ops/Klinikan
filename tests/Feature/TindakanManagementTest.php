<?php

namespace Tests\Feature;

use App\Models\Tindakan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TindakanManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_tindakan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.tindakan.store'), [
            'nama_tindakan' => 'Pemeriksaan Umum',
            'tarif' => 50000,
        ]);

        $response->assertRedirect(route('admin.tindakan.index'));
        $this->assertDatabaseHas('tindakans', [
            'nama_tindakan' => 'Pemeriksaan Umum',
            'tarif' => '50000.00',
        ]);
    }

    public function test_admin_cannot_create_duplicate_tindakan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Tindakan::create([
            'kode_tindakan' => 'TIN001',
            'nama_tindakan' => 'Pemeriksaan Umum',
            'tarif' => 50000,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.tindakan.store'), [
                'nama_tindakan' => 'Pemeriksaan Umum',
                'tarif' => 60000,
            ])
            ->assertSessionHasErrors('nama_tindakan');
    }
}
