<?php

namespace Tests\Feature;

use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->generateToken();
    }

    public function test_fetch_all_tasks_of_todo_list()
    {
        $list = $this->createTodoListFactory();
        $task = $this->createTaskFactory(['todo_list_id' => $list->id]);

        $response = $this->getJson(route('todo-list.tasks.index', $list->id))->assertOk()->json('data');
        // dd($response[0]['todo_list']);

        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
        $this->assertEquals($response[0]['todo_list']['name'], $list->name);
    }

    public function test_store_new_task_of_todo_list()
    {
        $task = factory(Task::class)->make();
        $list = $this->createTodoListFactory();
        $label = $this->createLabelFactory();

        $response = $this->postJson(route('todo-list.tasks.store', $list->id), [
            'title' => $task->title,
            'label_id' => $label->id
        ])->assertCreated()->json();

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
            'todo_list_id' => $list->id,
            'label_id' => $label->id
        ]);
        // $this->assertEquals($task->title, $response['title']);
    }

    public function test_store_new_task_of_todo_list_without_label_id()
    {
        $task = factory(Task::class)->make();
        $list = $this->createTodoListFactory();
        $label = $this->createLabelFactory();

        $response = $this->postJson(route('todo-list.tasks.store', $list->id), [
            'title' => $task->title,
        ])->assertCreated()->json();

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
            'todo_list_id' => $list->id,
        ]);
    }

    public function test_delete_task_of_todo_list()
    {
        $task = $this->createTaskFactory();
        $this->deleteJson(route('tasks.destroy', $task->id))->assertOk();
        $this->assertDatabaseMissing('tasks', ['title' => $task->title]);
    }

    public function test_update_task_of_todo_list()
    {
        $task = $this->createTaskFactory();

        $this->patchJson(route('tasks.update', $task->id), ['title' => 'Updated list'])->assertOk()->json();

        $this->assertDatabaseHas('tasks', ['title' => 'Updated list']);
    }
}
