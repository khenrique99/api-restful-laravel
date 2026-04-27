<?php

namespace Tests\Unit\Services\Vehicles;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Models\Vehicle;
use App\Services\Vehicles\UpdateVehicleService;
use Mockery;
use Tests\TestCase;

class UpdateVehicleServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_handle_updates_vehicle_and_returns_fresh_instance()
    {
        $vehicle = new Vehicle([
            'id' => 1,
            'name' => 'Old Name',
            'year' => 2020,
            'color' => 'Blue',
        ]);

        $data = ['name' => 'New Name'];

        $mockRepository = Mockery::mock(VehicleRepositoryInterface::class);
        $mockRepository->shouldReceive('update')->with(1, $data)->andReturn(true);

        $updatedVehicle = new Vehicle([
            'id' => 1,
            'name' => 'New Name',
            'year' => 2020,
            'color' => 'Blue',
        ]);

        $service = new UpdateVehicleService($mockRepository);
        $result = $service->handle($vehicle, $data);

        $this->assertInstanceOf(Vehicle::class, $result);
        $this->assertEquals('New Name', $result->name);
    }

    public function test_handle_with_multiple_fields()
    {
        $vehicle = new Vehicle([
            'id' => 2,
            'name' => 'Test Vehicle',
            'year' => 2020,
            'color' => 'Red',
        ]);

        $data = [
            'name' => 'Updated Vehicle',
            'year' => 2021,
            'color' => 'Green',
        ];

        $mockRepository = Mockery::mock(VehicleRepositoryInterface::class);
        $mockRepository->shouldReceive('update')->with(2, $data)->andReturn(true);

        $service = new UpdateVehicleService($mockRepository);
        $result = $service->handle($vehicle, $data);

        $this->assertEquals('Updated Vehicle', $result->name);
        $this->assertEquals(2021, $result->year);
        $this->assertEquals('Green', $result->color);
    }
}
