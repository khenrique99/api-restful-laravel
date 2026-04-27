<?php

namespace App\Services\Vehicles;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListVehiclesService
{
    protected VehicleRepositoryInterface $vehicleRepository;

    public function __construct(VehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function handle(int $perPage = 15): LengthAwarePaginator
    {
        return $this->vehicleRepository->paginate($perPage);
    }
}
