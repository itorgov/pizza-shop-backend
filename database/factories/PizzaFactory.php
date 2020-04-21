<?php

use App\Pizza;
use App\PizzaSize;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/** @var Factory $factory */
$factory->define(Pizza::class, function () {
    return [
        'name' => Arr::random([
            'All The Meats',
            'Garden Special',
            'Pepperoni',
            'Chicken BBQ',
            'Hawaiian',
            'Greek Treasure',
            'Little Italy',
            'Ham It Up',
            'Margherita',
            'New Orleans Jazz',
        ]),
        'toppings' => Arr::random([
            'Pepperoni, Greek Sausage, Ground Beef, Bacon & Mozzarella Cheese.',
            'Fresh Tomatoes, Fresh Mushrooms, Onions, Green Peppers, Black Olives & Mozzarella Cheese.',
            'Loaded with Pepperoni & Extra Mozzarella Cheese.',
            'Grilled Chicken, Bacon, Fresh Onions, BBQ Sauce drizzled on top & Mozzarella Cheese.',
            'Pineapple & Extra Mozzarella Cheese.',
            'Bacon, Feta Cheese, Fresh Tomatoes, Green Peppers, Fresh Mushrooms, Onions, Mozzarella Cheese & Oregano.',
            'Pepperoni, Italian Sausage, Fresh Mushrooms, Black olives, Mozzarella Cheese & Oregano.',
            'Fresh Mushrooms & Mozzarella Cheese.',
            'Fresh Tomatoes, Extra Cheese & Oregano.',
            'Grilled Chicken, Onions, Green Peppers, Garlic Sauce Drizzling & Mozzarella Cheese.',
        ]),
        'image_url' => 'https://loremflickr.com/800/530/food?random='.random_int(1, 1000),
    ];
});

$factory->state(Pizza::class, 'published', function () {
    return [
        'published_at' => Carbon::parse('-1 week'),
    ];
});

$factory->state(Pizza::class, 'unpublished', function () {
    return [
        'published_at' => null,
    ];
});

$factory->afterCreatingState(Pizza::class, 'has_sizes', function (Pizza $pizza) {
    // As a default it has to have 3 different sizes: 10, 12 and 14 inches.
    // Price should be relative to size (bigger size - bigger price).

    factory(PizzaSize::class)->create([
        'pizza_id' => $pizza->id,
        'size' => 10,
        'price_usd' => $price = 10 * random_int(100, 119),
        'price_eur' => $price * 0.9,
    ]);

    factory(PizzaSize::class)->create([
        'pizza_id' => $pizza->id,
        'size' => 12,
        'price_usd' => $price = 12 * random_int(100, 116),
        'price_eur' => $price * 0.9,
    ]);

    factory(PizzaSize::class)->create([
        'pizza_id' => $pizza->id,
        'size' => 14,
        'price_usd' => $price = 14 * random_int(100, 116),
        'price_eur' => $price * 0.9,
    ]);
});
