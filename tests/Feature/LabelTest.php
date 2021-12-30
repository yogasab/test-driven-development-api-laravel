<?php

namespace Tests\Feature;

use App\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->generateToken();
    }

    public function test_create_a_new_label()
    {
        $label = factory(Label::class)->raw();

        $response = $this->postJson(route('labels.store'), $label)->assertCreated()->json();

        $this->assertDatabaseHas('labels', $response);
    }

    public function test_user_can_delete_label()
    {
        $label = $this->createLabelFactory();

        $this->deleteJson(route('labels.destroy', $label->id))->assertNoContent();

        $this->assertDatabaseMissing('labels', ['name' => $label->name]);
    }

    public function test_user_can_update_label()
    {
        $label = $this->createLabelFactory();

        $this->putJson(route('labels.update', $label->id), [
            'name' => $label->name,
            'color' => 'New Color'
        ])->assertOk();
    }

    public function test_user_can_get_all_labels()
    {
        $label = $this->createLabelFactory(['user_id' => $this->user->id]);
        $this->createLabelFactory();

        $response = $this->getJson(route('labels.index'))->assertOk()->json();
        
        // Return label based on user created label
        $this->assertEquals($response[0]['name'], $label->name);
    }
}
