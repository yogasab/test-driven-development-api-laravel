<?php

namespace Tests\Feature\API;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_using_email_and_password()
    {
        $user = factory(User::class)->create();
        $response = $this->postJson(route('login.user'), [
            'email' => $user->email,
            'password' => 'password'
        ])->assertOk()->json();

        $this->assertArrayHasKey('token', $response);
    }

    public function test_user_if_not_authorized()
    {
        $this->postJson(route('login.user'), [
            'email' => 'random@email.com',
            'password' => 'qwertiojodj'
        ])->assertUnauthorized();
    }

    public function test_password_user_is_not_incorrect()
    {
        $user = factory(User::class)->create();

        $this->postJson(route('login.user'), [
            'email' => $user->email,
            'password' => 'wdwdwdwddwdwd'
        ])->assertUnauthorized();
    }
}
