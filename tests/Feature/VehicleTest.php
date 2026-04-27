<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_vehicle_success()
    {
        $payload = [
            'name' => 'Civic',
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => '2023',
            'color' => 'Gray',
            'price' => 75000.00,
        ];

        $response = $this->postJson('/api/vehicles', $payload);
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle created successfully.',
                'data' => [
                    'name' => 'Civic',
                    'brand' => 'Honda',
                    'model' => 'Civic',
                    'year' => '2023',
                    'color' => 'Gray',
                    'price' => 75000.00,
                ],
                'errors' => null,
            ]);

        $this->assertDatabaseHas('vehicles', $payload);
    }

    public function test_list_vehicles_success()
    {
        Vehicle::factory()->count(3)->create();

        $response = $this->getJson('/api/vehicles');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicles retrieved successfully.',
            ]);
    }

    public function test_show_vehicle_success()
    {
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create();

        $response = $this->getJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle retrieved successfully.',
                'data' => [
                    'id' => $vehicle->id,
                    'name' => $vehicle->name,
                    'year' => $vehicle->year,
                    'color' => $vehicle->color,
                ],
            ]);
    }

    public function test_update_vehicle_success()
    {
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create();

        $response = $this->patchJson("/api/vehicles/{$vehicle->id}", [
            'name' => 'Updated Civic',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Vehicle updated successfully.',
                'data' => [
                    'id' => $vehicle->id,
                    'name' => 'Updated Civic',
                ],
            ]);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'name' => 'Updated Civic',
        ]);
    }

    public function test_delete_vehicle_success()
    {
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create();

        $response = $this->deleteJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->id,
        ]);
    }

    public function test_create_vehicle_fails_when_name_is_missing()
    {
        $payload = [
            'year' => 2023,
            'color' => 'Gray',
        ];

        $response = $this->postJson('/api/vehicles', $payload);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => null,
                'errors' => [
                    'name' => ['Vehicle name is required.'],
                ],
            ]);
    }

    public function test_create_vehicle_fails_when_year_is_missing()
    {
        $payload = [
            'name' => 'Civic',
            'color' => 'Gray',
        ];

        $response = $this->postJson('/api/vehicles', $payload);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => null,
                'errors' => [
                    'year' => ['Vehicle year is required.'],
                ],
            ]);
    }

    public function test_create_vehicle_fails_when_color_is_missing()
    {
        $payload = [
            'name' => 'Civic',
            'year' => 2023,
        ];

        $response = $this->postJson('/api/vehicles', $payload);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => null,
                'errors' => [
                    'color' => ['Vehicle color is required.'],
                ],
            ]);
    }

    public function test_create_vehicle_fails_when_all_fields_are_missing()
    {
        $payload = [];

        $response = $this->postJson('/api/vehicles', $payload);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed.',
                'data' => null,
                'errors' => [
                    'name' => ['Vehicle name is required.'],
                    'year' => ['Vehicle year is required.'],
                    'color' => ['Vehicle color is required.'],
                ],
            ]);
    }

    public function test_create_vehicle_with_additional_fields_ignores_them()
    {
        $payload = [
            'name' => 'Civic',
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => '2023',
            'color' => 'Gray',
            'price' => 75000.00,
            'extra' => 'field',
        ];

        $response = $this->postJson('/api/vehicles', $payload);
        $response->assertStatus(201)->assertJson(['success' => true]);

        $this->assertDatabaseHas('vehicles', [
            'name' => 'Civic',
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => '2023',
            'color' => 'Gray',
        ]);
    }

    public function test_show_vehicle_returns_404_when_not_found()
    {
        $response = $this->getJson('/api/vehicles/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Vehicle not found.',
            ]);
    }

    public function test_update_vehicle_returns_404_when_not_found()
    {
        $response = $this->patchJson('/api/vehicles/999', [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Vehicle not found.',
            ]);
    }

    public function test_delete_vehicle_returns_404_when_not_found()
    {
        $response = $this->deleteJson('/api/vehicles/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Vehicle not found.',
            ]);
    }

    public function test_list_vehicles_returns_empty_when_no_vehicles()
    {
        $response = $this->getJson('/api/vehicles');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'data' => [],
                    'current_page' => 1,
                    'per_page' => 15,
                    'total' => 0,
                ],
            ]);
    }

    public function test_create_vehicle_fails_with_invalid_year()
    {
        $payload = [
            'name' => 'Civic',
            'year' => 'invalid',
            'color' => 'Gray',
        ];

        $response = $this->postJson('/api/vehicles', $payload);
        $response->assertStatus(422);
    }

    public function test_update_vehicle_fails_with_invalid_data()
    {
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create();

        $response = $this->patchJson("/api/vehicles/{$vehicle->id}", [
            'year' => 'not_a_number',
        ]);

        $response->assertStatus(422);
    }
}
