<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Label;
use App\User;
use Faker\Generator as Faker;

$factory->define(Label::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'color' => $faker->colorName,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        }
    ];
});
