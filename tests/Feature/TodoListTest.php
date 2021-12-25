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
        $this->list = factory(TodoList::class)->create(['name' => 'Coding Backend in Laravel']);
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
}
