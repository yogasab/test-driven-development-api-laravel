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
        $this->list = $this->createTodoListFactory(['name' => 'Coding Backend in Laravel']);
    }

    public function test_get_all_todo_lists_index()
    {
        // Preparation
        // factory(TodoList::class)->create(['name' => 'Coding Backend in Laravel']);
        // Action / perform
        $response = $this->getJson(route('todo-lists.index'));

        // Assertion / predict
        $this->assertEquals(1, count($response->json()));
        $this->assertEquals('Coding Backend in Laravel', $response->json()['lists'][0]['name']);
    }

    public function test_get_single_todo_list_show()
    {
        // Check if is it fetch it or failed
        $response = $this->getJson(route('todo-lists.show', $this->list->id))->assertOk()->json();

        // Check the current name
        $this->assertEquals($response['name'], $this->list->name);
    }

    public function test_store_new_todo_list()
    {
        $list = factory(TodoList::class)->make();

        $response = $this->postJson(route('todo-lists.store', ['name' => $list->name]))
            ->assertCreated()
            ->json();
        $this->assertEquals($list->name, $response['name']);
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
        $this->deleteJson(route('todo-lists.destroy', $this->list->id))->assertNoContent();
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
