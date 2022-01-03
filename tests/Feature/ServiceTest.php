<?php

namespace Tests\Feature;

use Google\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use ReflectionFunctionAbstract;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public const DRIVE_SCOPES = [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file'
    ];

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
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setClientId')->once();
            $mock->shouldReceive('setClientSecret')->once();
            $mock->shouldReceive('setRedirectUri')->once();
            $mock->shouldReceive('setScopes')->once();
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')->andReturn('fake-token');
        });

        $service = $this->postJson(route('service.callback', 'dummyCode'))->assertCreated();

        $this->assertDatabaseHas('services', [
            'user_id' => $this->user->id,
            'token' => $service['token'],
            'name' => $service['name']
        ]);
    }
}
