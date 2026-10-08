<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'pegawai_gudang',
        ]);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_new_users_can_register_with_admin_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Admin User',
            'email' => 'admin_test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
            'pekerjaan' => 'Pengelola BMN & Gudang Persediaan',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'admin_test@example.com',
            'role' => 'admin',
            'pekerjaan' => 'Pengelola BMN & Gudang Persediaan',
        ]);
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
