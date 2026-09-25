<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_active_user_can_login_and_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'active@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'active@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'status' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'บัญชีของคุณถูกระงับการใช้งาน']);
        $this->assertGuest();
    }

    public function test_dashboard_displays_user_statistics(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        User::factory()->create(['status' => 'inactive']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('totalUsers', 2);
        $response->assertViewHas('activeUsers', 1);
        $response->assertViewHas('inactiveUsers', 1);
    }

    public function test_users_index_displays_user_list(): void
    {
        $user = User::factory()->create(['name' => 'Alice Test', 'email' => 'alice@example.com']);
        $user2 = User::factory()->create(['name' => 'Bob Test', 'email' => 'bob@example.com']);

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Alice Test');
        $response->assertSee('Bob Test');
    }

    public function test_can_search_and_filter_users(): void
    {
        $admin = User::factory()->create(['name' => 'Admin Manager']);
        User::factory()->create(['name' => 'Charlie One', 'status' => 'active']);
        User::factory()->create(['name' => 'David Two', 'status' => 'inactive']);

        $response = $this->actingAs($admin)->get('/users?search=David');
        $response->assertSee('David Two');
        $response->assertDontSee('Charlie One');

        $filterResponse = $this->actingAs($admin)->get('/users?status=inactive');
        $filterResponse->assertSee('David Two');
        $filterResponse->assertDontSee('Charlie One');
    }

    public function test_can_create_new_user(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'status' => 'active',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'New User',
            'status' => 'active',
        ]);
    }

    public function test_can_update_user(): void
    {
        $admin = User::factory()->create();
        $targetUser = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->put("/users/{$targetUser->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'status' => 'inactive',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'status' => 'inactive',
        ]);
    }

    public function test_cannot_delete_self(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete("/users/{$user->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_can_delete_other_user(): void
    {
        $admin = User::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($admin)->delete("/users/{$otherUser->id}");

        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('users', ['id' => $otherUser->id]);
    }

    public function test_can_update_profile_and_password(): void
    {
        $user = User::factory()->create([
            'name' => 'My Old Name',
            'password' => Hash::make('oldpassword'),
        ]);

        $profileResponse = $this->actingAs($user)->put('/profile', [
            'name' => 'My New Name',
            'email' => $user->email,
        ]);

        $profileResponse->assertSessionHas('profile_success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'My New Name',
        ]);

        $passwordResponse = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $passwordResponse->assertSessionHas('password_success');
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }
}
