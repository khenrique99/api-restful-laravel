<?php

namespace App\Services\Vehicles;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Events\VehicleCreated;
use App\Models\Vehicle;

class CreateVehicleService
{
    protected VehicleRepositoryInterface $vehicleRepository;

    public function __construct(VehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function handle(array $data): Vehicle
    {
        $vehicle = $this->vehicleRepository->create($data);
        event(new VehicleCreated($vehicle));

        return $vehicle;
    }
}
