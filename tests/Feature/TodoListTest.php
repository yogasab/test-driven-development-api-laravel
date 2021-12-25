<?php

namespace Tests\Feature;

use App\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_todo_lists_index()
    {
        // Preparation
        factory(TodoList::class)->create(['name' => 'Coding Backend in Laravel']);
        // Action / perform
        $response = $this->getJson(route('todo-lists.index'));
        // Assertion / predict
        $this->assertEquals(1, count($response->json()));
        $this->assertEquals('Coding Backend in Laravel', $response->json()['lists'][0]['name']);
    }
}
