<?php

namespace App\Services\Vehicles;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Models\Vehicle;

class UpdateVehicleService
{
    protected VehicleRepositoryInterface $vehicleRepository;

    public function __construct(VehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function handle(Vehicle $vehicle, array $data): Vehicle
    {
        $this->vehicleRepository->update($vehicle->id, $data);

        return $vehicle->fresh();
    }
}
