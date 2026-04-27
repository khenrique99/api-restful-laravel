<?php

namespace Tests\Unit\Repositories;

use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected VehicleRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new VehicleRepository(new Vehicle);
    }

    public function test_all_returns_collection()
    {
        Vehicle::factory()->count(3)->create();

        $result = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_paginate_returns_paginator()
    {
        Vehicle::factory()->count(20)->create();

        $result = $this->repository->paginate(10);

        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(20, $result->total());
    }

    public function test_find_returns_vehicle_when_exists()
    {
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create();

        $result = $this->repository->find($vehicle->id);

        $this->assertInstanceOf(Vehicle::class, $result);
        $this->assertEquals($vehicle->id, $result->id);
    }

    public function test_find_returns_null_when_not_exists()
    {
        $result = $this->repository->find(999);

        $this->assertNull($result);
    }

    public function test_create_saves_and_returns_vehicle()
    {
        $data = [
            'name' => 'New Vehicle',
            'year' => 2023,
            'color' => 'Red',
        ];

        $result = $this->repository->create($data);

        $this->assertInstanceOf(Vehicle::class, $result);
        $this->assertEquals('New Vehicle', $result->name);
        $this->assertDatabaseHas('vehicles', $data);
    }

    public function test_update_returns_true_when_successful()
    {
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create(['name' => 'Old Name']);

        $result = $this->repository->update($vehicle->id, ['name' => 'New Name']);

        $this->assertTrue($result);
        $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id, 'name' => 'New Name']);
    }

    public function test_update_returns_false_when_vehicle_not_found()
    {
        $result = $this->repository->update(999, ['name' => 'New Name']);

        $this->assertFalse($result);
    }

    public function test_delete_returns_true_when_successful()
    {
        /** @var Vehicle $vehicle */
        $vehicle = Vehicle::factory()->create();

        $result = $this->repository->delete($vehicle->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }

    public function test_delete_returns_false_when_vehicle_not_found()
    {
        $result = $this->repository->delete(999);

        $this->assertFalse($result);
    }

    public function test_find_by_user_returns_user_vehicles()
    {
        $userId = 1;
        Vehicle::factory()->count(2)->create(['user_id' => $userId]);
        Vehicle::factory()->count(3)->create(['user_id' => 2]);

        $result = $this->repository->findByUser($userId);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $result->each(function ($vehicle) use ($userId) {
            $this->assertEquals($userId, $vehicle->user_id);
        });
    }
}
