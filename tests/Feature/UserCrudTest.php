<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_user(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => [
                    'name' => 'New User',
                    'email' => 'new@example.com',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
        ]);
    }

    public function test_authenticated_user_can_show_own_profile(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/users/'.$user->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
            ]);
    }

    public function test_user_cannot_show_other_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $other */
        $other = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/users/'.$other->id);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'This action is unauthorized.',
            ]);
    }

    public function test_authenticated_user_can_update_own_profile(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/users/'.$user->id, [
            'name' => 'Updated Name',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User updated successfully.',
                'data' => [
                    'name' => 'Updated Name',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_user_cannot_update_other_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $other */
        $other = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/users/'.$other->id, [
            'name' => 'Hacked Name',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'This action is unauthorized.',
            ]);
    }

    public function test_authenticated_user_can_delete_own_profile(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/api/users/'.$user->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_user_cannot_delete_other_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $other */
        $other = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/api/users/'.$other->id);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'This action is unauthorized.',
            ]);
    }

    public function test_show_user_returns_404_when_not_found()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/users/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'User not found.',
            ]);
    }

    public function test_update_user_returns_404_when_not_found()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/users/999', [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'User not found.',
            ]);
    }

    public function test_delete_user_returns_404_when_not_found()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/api/users/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'User not found.',
            ]);
    }

    public function test_register_fails_with_existing_email()
    {
        /** @var User $existingUser */
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ],
            ]);
    }
}
