<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'VehicleResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Toyota Corolla'),
        new OA\Property(property: 'brand', type: 'string', example: 'Toyota'),
        new OA\Property(property: 'model', type: 'string', example: 'Corolla'),
        new OA\Property(property: 'year', type: 'integer', example: 2020),
        new OA\Property(property: 'color', type: 'string', example: 'Red'),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 85000.00),
        new OA\Property(property: 'user_id', type: 'integer', nullable: true, example: null),
        new OA\Property(property: 'user', ref: '#/components/schemas/UserResource', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class VehicleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
            'price' => $this->price,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', fn () => $this->user),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
