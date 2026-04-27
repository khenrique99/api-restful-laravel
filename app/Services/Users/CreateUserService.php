<?php

namespace App\Services\Users;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;

class CreateUserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(array $data): User
    {
        $data['password'] = bcrypt($data['password']);

        return $this->userRepository->create($data);
    }
}
