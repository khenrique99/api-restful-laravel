<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Vehicle\CreateRequest;
use App\Http\Requests\Vehicle\UpdateRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Services\Vehicles\CreateVehicleService;
use App\Services\Vehicles\DeleteVehicleService;
use App\Services\Vehicles\ListVehiclesService;
use App\Services\Vehicles\UpdateVehicleService;
use OpenApi\Attributes as OA;

class VehicleController extends Controller
{
    protected CreateVehicleService $createService;

    protected ListVehiclesService $listService;

    protected UpdateVehicleService $updateService;

    protected DeleteVehicleService $deleteService;

    public function __construct(
        CreateVehicleService $create,
        ListVehiclesService $list,
        UpdateVehicleService $update,
        DeleteVehicleService $delete
    ) {
        $this->createService = $create;
        $this->listService = $list;
        $this->updateService = $update;
        $this->deleteService = $delete;
    }

    #[OA\Get(
        path: '/api/vehicles',
        summary: 'List all vehicles',
        tags: ['Vehicles'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vehicles retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Vehicles retrieved successfully.'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/VehicleResource')),
                    ]
                )
            ),
        ]
    )]
    public function index()
    {
        $vehicles = $this->listService->handle(15);

        return ApiResponse::success('Vehicles retrieved successfully.', VehicleResource::collection($vehicles));
    }

    #[OA\Post(
        path: '/api/vehicles',
        summary: 'Create a new vehicle',
        tags: ['Vehicles'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CreateRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Vehicle created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Vehicle created successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/VehicleResource'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(CreateRequest $request)
    {
        $vehicle = $this->createService->handle($request->validated());

        return ApiResponse::success('Vehicle created successfully.', new VehicleResource($vehicle), 201);
    }

    #[OA\Get(
        path: '/api/vehicles/{vehicle}',
        summary: 'Get a specific vehicle',
        tags: ['Vehicles'],
        parameters: [
            new OA\Parameter(name: 'vehicle', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vehicle retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Vehicle retrieved successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/VehicleResource'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Vehicle not found'),
        ]
    )]
    public function show(Vehicle $vehicle)
    {
        return ApiResponse::success('Vehicle retrieved successfully.', new VehicleResource($vehicle));
    }

    #[OA\Put(
        path: '/api/vehicles/{vehicle}',
        summary: 'Update a vehicle',
        tags: ['Vehicles'],
        parameters: [
            new OA\Parameter(name: 'vehicle', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vehicle updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Vehicle updated successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/VehicleResource'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Vehicle not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateRequest $request, Vehicle $vehicle)
    {
        $vehicle = $this->updateService->handle($vehicle, $request->validated());

        return ApiResponse::success('Vehicle updated successfully.', new VehicleResource($vehicle));
    }

    #[OA\Delete(
        path: '/api/vehicles/{vehicle}',
        summary: 'Delete a vehicle',
        tags: ['Vehicles'],
        parameters: [
            new OA\Parameter(name: 'vehicle', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Vehicle deleted successfully'),
            new OA\Response(response: 404, description: 'Vehicle not found'),
        ]
    )]
    public function destroy(Vehicle $vehicle)
    {
        $this->deleteService->handle($vehicle);

        return ApiResponse::noContent();
    }

    #[OA\Post(
        path: '/api/vehicles/{vehicle}/buy',
        summary: 'Buy a vehicle',
        tags: ['Vehicles'],
        parameters: [
            new OA\Parameter(name: 'vehicle', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vehicle purchased successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Vehicle purchased successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/VehicleResource'),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Vehicle not available for purchase'),
            new OA\Response(response: 404, description: 'Vehicle not found'),
        ]
    )]
    public function buy(Vehicle $vehicle)
    {
        // Check if vehicle is available for purchase (no owner)
        if ($vehicle->user_id !== null) {
            return ApiResponse::error('Vehicle is not available for purchase.', null, 400);
        }

        // Assign vehicle to authenticated user
        $vehicle->update(['user_id' => auth()->id()]);
        $vehicle->load('user');

        return ApiResponse::success('Vehicle purchased successfully.', new VehicleResource($vehicle));
    }

    #[OA\Post(
        path: '/api/vehicles/{vehicle}/sell',
        summary: 'Sell a vehicle',
        tags: ['Vehicles'],
        parameters: [
            new OA\Parameter(name: 'vehicle', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vehicle sold successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Vehicle sold successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/VehicleResource'),
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'You can only sell your own vehicles'),
            new OA\Response(response: 404, description: 'Vehicle not found'),
        ]
    )]
    public function sell(Vehicle $vehicle)
    {
        // Check if vehicle belongs to authenticated user
        if ($vehicle->user_id !== auth()->id()) {
            return ApiResponse::error('You can only sell your own vehicles.', null, 403);
        }

        // Remove ownership (make vehicle available for purchase)
        $vehicle->update(['user_id' => null]);

        return ApiResponse::success('Vehicle sold successfully.', new VehicleResource($vehicle));
    }
}
