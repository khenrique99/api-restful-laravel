<?php

namespace App\Events;

use App\Models\Vehicle;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VehicleCreated
{
    use Dispatchable, SerializesModels;

    public Vehicle $vehicle;

    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }
}
