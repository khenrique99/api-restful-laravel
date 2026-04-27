<?php

namespace App\Contracts\Repositories;

use App\Models\Vehicle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface VehicleRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Vehicle;

    public function create(array $data): Vehicle;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function findByUser(int $userId): Collection;
}
