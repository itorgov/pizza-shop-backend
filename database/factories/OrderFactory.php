<?php

use App\Order;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Arr;

/** @var Factory $factory */

$factory->define(Order::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'currency' => Arr::random(['usd', 'eur']),
        'delivery_fee' => random_int(200, 1000),
    ];
});
