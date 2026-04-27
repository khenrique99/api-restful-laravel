<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehiclePurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_buy_available_vehicle()
    {
        // Create a user and a vehicle without owner
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create(['user_id' => null]);

        // Authenticate user and buy vehicle
        $response = $this->actingAs($user, 'web')
            ->postJson("/api/vehicles/{$vehicle->id}/buy");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle purchased successfully.',
                'data' => [
                    'id' => $vehicle->id,
                    'user_id' => $user->id,
                ],
            ]);

        // Verify vehicle now belongs to user
        $this->assertEquals($user->id, $vehicle->fresh()->user_id);
    }

    public function test_user_cannot_buy_owned_vehicle()
    {
        // Create two users and a vehicle owned by one of them
        /** @var User $owner */
        $owner = User::factory()->create();
        /** @var User $buyer */
        $buyer = User::factory()->create();
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create(['user_id' => $owner->id]);

        // Try to buy vehicle as another user
        $response = $this->actingAs($buyer, 'web')
            ->postJson("/api/vehicles/{$vehicle->id}/buy");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Vehicle is not available for purchase.',
            ]);

        // Verify vehicle still belongs to original owner
        $this->assertEquals($owner->id, $vehicle->fresh()->user_id);
    }

    public function test_user_can_sell_owned_vehicle()
    {
        // Create a user and a vehicle owned by them
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        // Sell the vehicle
        $response = $this->actingAs($user, 'web')
            ->postJson("/api/vehicles/{$vehicle->id}/sell");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle sold successfully.',
                'data' => [
                    'id' => $vehicle->id,
                    'user_id' => null,
                ],
            ]);

        // Verify vehicle is now available for purchase
        $this->assertNull($vehicle->fresh()->user_id);
    }

    public function test_user_cannot_sell_vehicle_they_dont_own()
    {
        // Create two users and a vehicle owned by one of them
        /** @var User $owner */
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create(['user_id' => $owner->id]);

        // Try to sell vehicle as another user
        $response = $this->actingAs($otherUser, 'web')
            ->postJson("/api/vehicles/{$vehicle->id}/sell");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'You can only sell your own vehicles.',
            ]);

        // Verify vehicle still belongs to original owner
        $this->assertEquals($owner->id, $vehicle->fresh()->user_id);
    }

    public function test_buy_sell_requires_authentication()
    {
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create(['user_id' => null]);

        // Try to buy without authentication
        $response = $this->postJson("/api/vehicles/{$vehicle->id}/buy");
        $response->assertStatus(401);

        // Try to sell without authentication
        $response = $this->postJson("/api/vehicles/{$vehicle->id}/sell");
        $response->assertStatus(401);
    }
}
