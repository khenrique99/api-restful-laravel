<?php

namespace Tests\Unit\Events;

use App\Events\VehicleCreated;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleCreatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_created_event_has_vehicle()
    {
        $vehicle = Vehicle::factory()->create();

        $event = new VehicleCreated($vehicle);

        $this->assertInstanceOf(Vehicle::class, $event->vehicle);
        $this->assertEquals($vehicle->id, $event->vehicle->id);
        $this->assertEquals($vehicle->name, $event->vehicle->name);
    }

    public function test_event_uses_dispatchable_trait()
    {
        $vehicle = Vehicle::factory()->create();
        $event = new VehicleCreated($vehicle);

        $this->assertContains('Illuminate\Foundation\Events\Dispatchable', class_uses($event));
    }

    public function test_event_uses_serializes_models_trait()
    {
        $vehicle = Vehicle::factory()->create();
        $event = new VehicleCreated($vehicle);

        $this->assertContains('Illuminate\Queue\SerializesModels', class_uses($event));
    }
}
