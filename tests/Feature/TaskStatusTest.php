<?php

namespace Tests\Feature;

use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_status_can_be_changed()
    {
        $this->generateToken();
        $task = $this->createTaskFactory();

        $response  = $this->patchJson(route('tasks.update', $task->id), ['status' => Task::STARTED]);

        $this->assertDatabaseHas('tasks', ['status' => Task::STARTED]);
    }
}
