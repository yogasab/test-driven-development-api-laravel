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
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setScopes')->once();
            $mock->shouldReceive('createAuthUrl')->andReturn('http://127.0.0.1:8000/connect');
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')->andReturn('fake-token');
        });

        $response = $this->getJson(route('service.connect', 'google-drive'))
            ->assertOk()
            ->json();

        $this->assertEquals($response['url'], 'http://127.0.0.1:8000/connect');
        $this->assertNotNull($response['url']);
    }

    public function test_service_callback_will_store_token()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setScopes')->once();
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')->andReturn('fake-token');
        });

        $service = $this->postJson(route('service.callback', 'dummyCode'))->assertCreated();

        $this->assertDatabaseHas('services', [
            'user_id' => $this->user->id,
            'token' => "\"{\\\"access_token\\\":\\\"fake-token\\\"}\"",
            'name' => $service['name']
        ]);
    }

    public function test_user_can_upload_files_to_google_drive()
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setAccessToken')->once();
            $mock->shouldReceive('getLogger->info')->once();
            $mock->shouldReceive('shouldDefer')->once();
            $mock->shouldReceive('execute')->once();
        });
        $service = $this->createServiceFactory();

        $this->postJson(route('service.upload', $service->id))->assertCreated();
    }
}
