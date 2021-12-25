<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_todo_lists_index()
    {
        // Preparation
        // Action / perform
        $response = $this->getJson(route('todo-lists.index'));
        // Assertion / predict
        $this->assertEquals(1, count($response->json()));
    }
}
