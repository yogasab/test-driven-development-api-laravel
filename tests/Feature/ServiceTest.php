<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use ReflectionFunctionAbstract;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->generateToken();
    }

    public function test_user_can_connect_to_services_and_token_is_stored()
    {
        $response = $this->getJson(route('service.connect', 'google-drive'))
            ->assertOk()
            ->json();

        $this->assertNotNull($response['url']);
    }

    public function test_service_callback_will_store_token()
    {
        $this->postJson(route('service.callback', 'dummyCode'))->assertCreated();

        $this->assertDatabaseHas('services', ['user_id' => $this->user->id]);
    }
}
