<?php

namespace Tests\Unit;

use App\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_todo_list_can_belongs_to_task()
    {
        $list = $this->createTodoListFactory();
        $task = $this->createTaskFactory(['todo_list_id' => $list->id]);

        $this->assertInstanceOf(TodoList::class, $task->todo_list);
    }
}
