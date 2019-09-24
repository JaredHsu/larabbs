<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    return [
        // 'name' => $faker->name,
        'title' => $faker->sentence(),
        'body' => $faker->text(),
        'excerpt' => $faker->sentence(),
        'created_at' => $faker->dateTimeThisMonth(),
        'updated_at' => $faker->dateTimeThisMonth(),
    ];
});
