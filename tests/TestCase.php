<?php

namespace Tests;

use App\TodoList;
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
}
