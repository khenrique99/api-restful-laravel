<?php

namespace App\Providers;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Models\Vehicle;
use App\Policies\VehiclePolicy;
use App\Repositories\UserRepository;
use App\Repositories\VehicleRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(VehicleRepositoryInterface::class, VehicleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Vehicle::class, VehiclePolicy::class);
    }
}
