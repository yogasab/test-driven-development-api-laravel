<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_a_new_label()
    {
        $user = $this->generateToken();
        // $label = $this->createLabelFactory(['name' => 'Personal', 'color' => 'Green']);

        $response = $this->postJson(route('labels.store'), [
            'name' => 'Personal',
            'color' => 'Green'
        ])->assertCreated()->json();

        $this->assertDatabaseHas('labels', $response);
    }
}
