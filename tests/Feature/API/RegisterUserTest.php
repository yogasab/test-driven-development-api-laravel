<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $this->withoutExceptionHandling();

        $this->postJson(route('register.user'), [
            'name' => 'Yoga Baskoro',
            'email' => 'yoga@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertCreated();

        $this->assertDatabaseHas('users', ['name' => 'Yoga Baskoro']);
    }
}
