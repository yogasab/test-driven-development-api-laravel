<?php

namespace Tests\Feature;

use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_all_tasks_of_todo_list()
    {
        $task = $this->createTaskFactory();
        $list = $this->createTodoListFactory();

        $response = $this->getJson(route('todo-list.tasks.index', $list->id))->assertOk()->json();

        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
    }

    public function test_store_new_task_of_todo_list()
    {
        $task = factory(Task::class)->make();
        $list = $this->createTodoListFactory();

        $response = $this->postJson(route('todo-list.tasks.store', $list->id), ['title' => $task->title])->assertCreated()->json();

        $this->assertDatabaseHas('tasks', ['title' => $task->title]);
        $this->assertEquals($task->title, $response['title']);
    }

    public function test_delete_task_of_todo_list()
    {
        $task = $this->createTaskFactory();
        $this->deleteJson(route('tasks.destroy', $task->id))->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['title' => $task->title]);
    }

    public function test_update_task_of_todo_list()
    {
        $task = $this->createTaskFactory();

        $response = $this->patchJson(route('tasks.update', $task->id), ['title' => 'Updated list'])->assertOk()->json();

        $this->assertDatabaseHas('tasks', ['title' => 'Updated list']);
    }
}
