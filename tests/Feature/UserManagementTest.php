<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user_from_data_pengguna(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.user.store'), [
            'name' => 'Dokter Baru',
            'email' => 'dokter@example.test',
            'role' => 'dokter',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.user.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Dokter Baru',
            'email' => 'dokter@example.test',
            'role' => 'dokter',
        ]);
        $this->assertTrue(Hash::check('password123', User::where('email', 'dokter@example.test')->first()->password));
    }

    public function test_non_admin_cannot_access_data_pengguna(): void
    {
        $user = User::factory()->create(['role' => 'pasien']);

        $this->actingAs($user)
            ->get(route('admin.user.index'))
            ->assertForbidden();
    }
}
