<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Users\CreateUserService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    protected CreateUserService $createUserService;

    public function __construct(CreateUserService $createUserService)
    {
        $this->createUserService = $createUserService;
    }

    #[OA\Post(
        path: '/api/register',
        summary: 'Register a new user',
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreUserRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'User created successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreUserRequest $request)
    {
        $user = $this->createUserService->handle($request->validated());

        return ApiResponse::success('User created successfully.', new UserResource($user), 201);
    }

    #[OA\Get(
        path: '/api/users/{user}',
        summary: 'Get authenticated user profile',
        tags: ['Users'],
        security: [['session' => []]],
        parameters: [
            new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'User retrieved successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'User not found'),
        ]
    )]
    public function show(Request $request, User $user)
    {
        if ($request->user()->id !== $user->id) {
            return ApiResponse::error('This action is unauthorized.', null, 403);
        }

        return ApiResponse::success('User retrieved successfully.', new UserResource($user));
    }

    #[OA\Put(
        path: '/api/users/{user}',
        summary: 'Update the authenticated user profile',
        tags: ['Users'],
        security: [['session' => []]],
        parameters: [
            new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateUserRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'User updated successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request->user()->id !== $user->id) {
            return ApiResponse::error('This action is unauthorized.', null, 403);
        }

        $data = $request->validated();

        if (empty($data)) {
            return ApiResponse::success('No changes detected.', new UserResource($user));
        }

        $user->update($data);

        return ApiResponse::success('User updated successfully.', new UserResource($user));
    }

    #[OA\Delete(
        path: '/api/users/{user}',
        summary: 'Delete the authenticated user',
        tags: ['Users'],
        security: [['session' => []]],
        parameters: [
            new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'User deleted successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'User not found'),
        ]
    )]
    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id !== $user->id) {
            return ApiResponse::error('This action is unauthorized.', null, 403);
        }

        $user->delete();

        return ApiResponse::success('User deleted successfully.');
    }
}
