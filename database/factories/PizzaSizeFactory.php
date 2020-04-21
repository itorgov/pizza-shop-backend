<?php

use App\Pizza;
use App\PizzaSize;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Arr;

/** @var Factory $factory */
$factory->define(PizzaSize::class, function (Faker $faker) {
    $size = Arr::random([10, 12, 14]);

    return [
        'pizza_id' => function () {
            return factory(Pizza::class)->create()->id;
        },
        'size' => $size,
        'price_usd' => $faker->randomFloat(2, 1.01, 1.2) * $size * 100,
        'price_eur' => $faker->randomFloat(2, 1.01, 1.2) * $size * 100,
    ];
});
