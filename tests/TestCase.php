<?php

namespace Tests;

use App\Task;
use App\TodoList;
use App\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function createTodoListFactory($args = [])
    {
        return factory(TodoList::class)->create($args);
    }

    public function createTaskFactory($args = [])
    {
        return factory(Task::class)->create($args);
    }

    public function createUser($args = [])
    {
        return factory(User::class)->create($args);
    }

    public function generateToken()
    {
        $user = $this->createUser();
        Sanctum::actingAs($user);
        return $user;
    }
}
