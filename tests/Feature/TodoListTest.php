<?php

namespace Tests\Feature;

use App\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public $list;

    public function setUp(): void
    {
        parent::setUp();
        $user = $this->generateToken();
        $this->list = $this->createTodoListFactory([
            'name' => 'Coding Backend in Laravel',
            'user_id' => $user->id
        ]);
    }

    public function test_get_all_todo_lists_index()
    {
        // Preparation
        factory(TodoList::class)->create();

        // Action / perform
        $this->createTodoListFactory();
        $response = $this->getJson(route('todo-lists.index'))->json('data');

        // Assertion / predict
        $this->assertEquals(1, count($response));
        $this->assertEquals('Coding Backend in Laravel', $response[0]['title']);
    }

    public function test_get_single_todo_list_show()
    {
        // Check if is it fetch it or failed
        $response = $this->getJson(route('todo-lists.show', $this->list->id))->assertOk()->json('data');

        // Check the current name
        $this->assertEquals($response['title'], $this->list->name);
    }

    public function test_store_new_todo_list()
    {
        // $user = $this->generateToken();
        $list = factory(TodoList::class)->make();

        $response = $this->postJson(route('todo-lists.store', [
            'name' => $list->name,
            // 'user_id' => $user->id
        ]))
            ->assertCreated()
            ->json('data');

        $this->assertEquals($list->name, $response['title']);
        $this->assertDatabaseHas('todo_lists', ['name' => $list->name]);
    }

    public function test_validation_is_required_in_store_todo_list()
    {
        $this->withExceptionHandling();

        $response = $this->postJson(route('todo-lists.store'))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_delete_todo_list_destroy()
    {
        $this->deleteJson(route('todo-lists.destroy', $this->list->id))->assertOk();

        $this->assertDatabaseMissing('todo_lists', ['name' => $this->list->name]);
    }

    public function test_update_todo_list_update()
    {
        $this->patchJson(route('todo-lists.update', $this->list->id), ['name' => 'Updated list'])->assertOk();
        $this->assertDatabaseHas('todo_lists', ['name' => 'Updated list']);
    }

    public function test_validation_is_required_in_update_todo_list()
    {
        $this->withExceptionHandling();
        $this->patchJson(route('todo-lists.update', $this->list->id))
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }
}
