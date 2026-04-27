<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository(new User);
    }

    public function test_all_returns_collection()
    {
        User::factory()->count(3)->create();

        $result = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
    }

    public function test_find_returns_user_when_exists()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $result = $this->repository->find($user->id);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
    }

    public function test_find_returns_null_when_not_exists()
    {
        $result = $this->repository->find(999);

        $this->assertNull($result);
    }

    public function test_find_by_email_returns_user_when_exists()
    {
        /** @var User $user */
        $user = User::factory()->create(['email' => 'test@example.com']);

        $result = $this->repository->findByEmail('test@example.com');

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('test@example.com', $result->email);
    }

    public function test_find_by_email_returns_null_when_not_exists()
    {
        $result = $this->repository->findByEmail('nonexistent@example.com');

        $this->assertNull($result);
    }

    public function test_create_saves_and_returns_user()
    {
        $data = [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'hashedpassword',
        ];

        $result = $this->repository->create($data);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('New User', $result->name);
        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'new@example.com',
        ]);
    }

    public function test_update_returns_true_when_successful()
    {
        /** @var User $user */
        $user = User::factory()->create(['name' => 'Old Name']);

        $result = $this->repository->update($user->id, ['name' => 'New Name']);

        $this->assertTrue($result);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
    }

    public function test_update_returns_false_when_user_not_found()
    {
        $result = $this->repository->update(999, ['name' => 'New Name']);

        $this->assertFalse($result);
    }

    public function test_delete_returns_true_when_successful()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $result = $this->repository->delete($user->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_delete_returns_false_when_user_not_found()
    {
        $result = $this->repository->delete(999);

        $this->assertFalse($result);
    }
}
