<?php

namespace App\Services\Vehicles;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Models\Vehicle;

class DeleteVehicleService
{
    protected VehicleRepositoryInterface $vehicleRepository;

    public function __construct(VehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function handle(Vehicle $vehicle): bool
    {
        return $this->vehicleRepository->delete($vehicle->id);
    }
}
