<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Service;
use App\User;
use Faker\Generator as Faker;

$factory->define(Service::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'name' => 'google-drive',
        'token' => ['access_token' => 'dummyToken']
    ];
});
