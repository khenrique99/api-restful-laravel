<?php

namespace Tests\Unit\Services\Users;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use App\Services\Users\CreateUserService;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class CreateUserServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_handle_creates_user_with_hashed_password()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $expectedData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ];

        $mockRepository = Mockery::mock(UserRepositoryInterface::class);
        $user = new User($expectedData);
        $user->id = 1;
        $mockRepository->shouldReceive('create')->with(Mockery::on(function ($arg) use ($expectedData) {
            return $arg['name'] === $expectedData['name'] &&
                   $arg['email'] === $expectedData['email'] &&
                   Hash::check('password123', $arg['password']);
        }))->andReturn($user);

        $service = new CreateUserService($mockRepository);
        $result = $service->handle($data);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('Test User', $result->name);
        $this->assertEquals('test@example.com', $result->email);
    }

    public function test_handle_with_minimal_data()
    {
        $data = [
            'name' => 'Minimal User',
            'email' => 'minimal@example.com',
            'password' => 'pass',
        ];

        $mockRepository = Mockery::mock(UserRepositoryInterface::class);
        $user = new User($data);
        $user->id = 2;
        $mockRepository->shouldReceive('create')->andReturn($user);

        $service = new CreateUserService($mockRepository);
        $result = $service->handle($data);

        $this->assertEquals('Minimal User', $result->name);
    }
}
