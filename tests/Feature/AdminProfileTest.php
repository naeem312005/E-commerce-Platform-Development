<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_profile(): void
    {
        $response = $this->get(route('admin.profile.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@banglapuzzle.com',
        ]);

        $response = $this->actingAs($user)->get(route('admin.profile.index'));

        $response->assertOk();
        $response->assertSee('Admin User');
        $response->assertSee('admin@banglapuzzle.com');
        $response->assertSee('Profile Settings');
    }

    public function test_admin_can_update_profile_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@banglapuzzle.com',
        ]);

        $response = $this->actingAs($user)->put(route('admin.profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@banglapuzzle.com',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@banglapuzzle.com',
        ]);
    }

    public function test_admin_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $response = $this->actingAs($user)->put(route('admin.profile.password'), [
            'current_password' => 'old-password-123',
            'password' => 'new-strong-password-456',
            'password_confirmation' => 'new-strong-password-456',
        ]);

        $response->assertSessionHas('success');
        $user->refresh();
        $this->assertTrue(Hash::check('new-strong-password-456', $user->password));
    }

    public function test_admin_cannot_update_password_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password-123'),
        ]);

        $response = $this->actingAs($user)->put(route('admin.profile.password'), [
            'current_password' => 'wrong-password',
            'password' => 'new-strong-password-456',
            'password_confirmation' => 'new-strong-password-456',
        ]);

        $response->assertSessionHas('error');
        $user->refresh();
        $this->assertTrue(Hash::check('correct-password-123', $user->password));
    }
}
