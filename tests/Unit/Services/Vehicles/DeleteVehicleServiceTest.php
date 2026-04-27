<?php

namespace Tests\Unit\Services\Vehicles;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Models\Vehicle;
use App\Services\Vehicles\DeleteVehicleService;
use Mockery;
use Tests\TestCase;

class DeleteVehicleServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_handle_deletes_vehicle_successfully()
    {
        $vehicle = new Vehicle(['id' => 1]);

        $mockRepository = Mockery::mock(VehicleRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')->with(1)->andReturn(true);

        $service = new DeleteVehicleService($mockRepository);
        $result = $service->handle($vehicle);

        $this->assertTrue($result);
    }

    public function test_handle_returns_false_when_delete_fails()
    {
        $vehicle = new Vehicle(['id' => 2]);

        $mockRepository = Mockery::mock(VehicleRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')->with(2)->andReturn(false);

        $service = new DeleteVehicleService($mockRepository);
        $result = $service->handle($vehicle);

        $this->assertFalse($result);
    }
}
