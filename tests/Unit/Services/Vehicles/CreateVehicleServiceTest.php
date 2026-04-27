<?php

namespace Tests\Unit\Services\Vehicles;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Events\VehicleCreated;
use App\Models\Vehicle;
use App\Services\Vehicles\CreateVehicleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class CreateVehicleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_handle_creates_vehicle_and_fires_event()
    {
        Event::fake();

        $data = [
            'name' => 'Test Vehicle',
            'brand' => 'Test Brand',
            'model' => 'Test Model',
            'year' => 2023,
            'color' => 'Red',
            'price' => 50000.00,
        ];

        $mockRepository = Mockery::mock(VehicleRepositoryInterface::class);
        $vehicle = new Vehicle($data);
        $vehicle->id = 1;
        $mockRepository->shouldReceive('create')->with($data)->andReturn($vehicle);

        $service = new CreateVehicleService($mockRepository);
        $result = $service->handle($data);

        $this->assertInstanceOf(Vehicle::class, $result);
        $this->assertEquals('Test Vehicle', $result->name);

        Event::assertDispatched(VehicleCreated::class, function ($event) use ($vehicle) {
            return $event->vehicle->id === $vehicle->id;
        });
    }

    public function test_handle_with_minimal_data()
    {
        Event::fake();

        $data = [
            'name' => 'Minimal Vehicle',
            'year' => 2020,
            'color' => 'Blue',
        ];

        $mockRepository = Mockery::mock(VehicleRepositoryInterface::class);
        $vehicle = new Vehicle($data);
        $vehicle->id = 2;
        $mockRepository->shouldReceive('create')->with($data)->andReturn($vehicle);

        $service = new CreateVehicleService($mockRepository);
        $result = $service->handle($data);

        $this->assertEquals('Minimal Vehicle', $result->name);
        Event::assertDispatched(VehicleCreated::class);
    }
}
