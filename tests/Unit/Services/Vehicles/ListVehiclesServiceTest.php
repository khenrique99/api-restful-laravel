<?php

namespace Tests\Unit\Services\Vehicles;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Services\Vehicles\ListVehiclesService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class ListVehiclesServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_handle_returns_paginated_vehicles()
    {
        $mockPaginator = Mockery::mock(LengthAwarePaginator::class);

        $mockRepository = Mockery::mock(VehicleRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')->with(15)->andReturn($mockPaginator);

        $service = new ListVehiclesService($mockRepository);
        $result = $service->handle();

        $this->assertSame($mockPaginator, $result);
    }

    public function test_handle_with_custom_per_page()
    {
        $mockPaginator = Mockery::mock(LengthAwarePaginator::class);

        $mockRepository = Mockery::mock(VehicleRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')->with(10)->andReturn($mockPaginator);

        $service = new ListVehiclesService($mockRepository);
        $result = $service->handle(10);

        $this->assertSame($mockPaginator, $result);
    }
}
