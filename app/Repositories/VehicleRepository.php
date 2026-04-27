<?php

namespace App\Repositories;

use App\Contracts\Repositories\VehicleRepositoryInterface;
use App\Models\Vehicle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class VehicleRepository implements VehicleRepositoryInterface
{
    protected Vehicle $model;

    public function __construct(Vehicle $vehicle)
    {
        $this->model = $vehicle;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id): ?Vehicle
    {
        return $this->model->find($id);
    }

    public function create(array $data): Vehicle
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $vehicle = $this->find($id);

        return $vehicle ? $vehicle->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $vehicle = $this->find($id);

        return $vehicle ? $vehicle->delete() : false;
    }

    public function findByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }
}
