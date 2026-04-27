<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Vehicle;
use App\Policies\VehiclePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehiclePolicyTest extends TestCase
{
    use RefreshDatabase;

    protected VehiclePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new VehiclePolicy;
    }

    public function test_view_any_is_allowed_for_any_user()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $result = $this->policy->viewAny($user);

        $this->assertTrue($result);
    }

    public function test_view_is_allowed_for_vehicle_owner()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $result = $this->policy->view($user, $vehicle);

        $this->assertTrue($result);
    }

    public function test_view_is_denied_for_non_owner()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $owner */
        $owner = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $owner->id]);

        $result = $this->policy->view($user, $vehicle);

        $this->assertFalse($result);
    }

    public function test_create_is_allowed_for_any_user()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $result = $this->policy->create($user);

        $this->assertTrue($result);
    }

    public function test_update_is_allowed_for_vehicle_owner()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $result = $this->policy->update($user, $vehicle);

        $this->assertTrue($result);
    }

    public function test_update_is_denied_for_non_owner()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $owner */
        $owner = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $owner->id]);

        $result = $this->policy->update($user, $vehicle);

        $this->assertFalse($result);
    }

    public function test_delete_is_allowed_for_vehicle_owner()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $result = $this->policy->delete($user, $vehicle);

        $this->assertTrue($result);
    }

    public function test_delete_is_denied_for_non_owner()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $owner */
        $owner = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $owner->id]);

        $result = $this->policy->delete($user, $vehicle);

        $this->assertFalse($result);
    }
}
