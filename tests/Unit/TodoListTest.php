<?php

namespace Tests\Unit;

use App\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_todo_list_can_has_many_task()
    {
        $list = $this->createTodoListFactory();
        $task = $this->createTaskFactory(['todo_list_id' => $list->id]);

        $this->assertInstanceOf(Collection::class, $list->tasks);
        $this->assertInstanceOf(Task::class, $list->tasks->first());
    }

    public function test_delete_todo_list_along_with_its_tasks()
    {
        $list = $this->createTodoListFactory();
        $task = $this->createTaskFactory(['todo_list_id' => $list->id]);
        $task2 = $this->createTaskFactory();

        $list->delete();

        $this->assertDatabaseMissing('todo_lists', ['id' => $list->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $this->assertDatabaseHas('tasks', ['id' => $task2->id]);
    }
}
