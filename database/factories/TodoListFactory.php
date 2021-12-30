<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TodoList;
use App\User;
use Faker\Generator as Faker;

$factory->define(TodoList::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        }
    ];
});
