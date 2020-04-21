<?php

use App\Pizza;
use Illuminate\Database\Seeder;

class PizzasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'All The Meats',
            'toppings' => 'Pepperoni, Greek Sausage, Ground Beef, Bacon & Mozzarella Cheese.',
            'image_url' => 'https://res.cloudinary.com/itorgov/image/upload/v1587129060/Pizza%20Shop%20Vue%20app/pizza-10_zqzrbt.jpg',
        ]);

        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'Garden Special',
            'toppings' => 'Fresh Tomatoes, Fresh Mushrooms, Onions, Green Peppers, Black Olives & Mozzarella Cheese.',
            'image_url' => 'https://res.cloudinary.com/itorgov/image/upload/v1587129058/Pizza%20Shop%20Vue%20app/pizza-4_eyxqtk.jpg',
        ]);

        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'Pepperoni',
            'toppings' => 'Loaded with Pepperoni & Extra Mozzarella Cheese.',
            'image_url' => 'https://res.cloudinary.com/itorgov/image/upload/v1587129058/Pizza%20Shop%20Vue%20app/pizza-1_wm0o8b.jpg',
        ]);

        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'Chicken BBQ',
            'toppings' => 'Grilled Chicken, Bacon, Fresh Onions, BBQ Sauce drizzled on top & Mozzarella Cheese.',
            'image_url' => 'https://res.cloudinary.com/itorgov/image/upload/v1587129058/Pizza%20Shop%20Vue%20app/pizza-7_ppf2sy.jpg',
        ]);

        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'Hawaiian',
            'toppings' => 'Pineapple & Extra Mozzarella Cheese.',
            'image_url' => 'https://res.cloudinary.com/itorgov/image/upload/v1587129059/Pizza%20Shop%20Vue%20app/pizza-6_kxgqxr.jpg',
        ]);

        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'Greek Treasure',
            'toppings' => 'Bacon, Feta Cheese, Fresh Tomatoes, Green Peppers, Fresh Mushrooms, Onions, Mozzarella Cheese & Oregano.',
            'image_url' => 'https://res.cloudinary.com/itorgov/image/upload/v1587129058/Pizza%20Shop%20Vue%20app/pizza-3_l2qic2.jpg',
        ]);

        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'Little Italy',
            'toppings' => 'Pepperoni, Italian Sausage, Fresh Mushrooms, Black olives, Mozzarella Cheese & Oregano.',
            'image_url' => 'https://res.cloudinary.com/itorgov/image/upload/v1587129059/Pizza%20Shop%20Vue%20app/pizza-2_uqdyx7.jpg',
        ]);

        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'Ham It Up',
            'toppings' => 'Fresh Mushrooms & Mozzarella Cheese.',
            'image_url' => 'https://res.cloudinary.com/itorgov/image/upload/v1587129060/Pizza%20Shop%20Vue%20app/pizza-5_cpki9r.jpg',
        ]);

        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'Margherita',
            'toppings' => 'Fresh Tomatoes, Extra Cheese & Oregano.',
            'image_url' => 'https://res.cloudinary.com/itorgov/image/upload/v1587129059/Pizza%20Shop%20Vue%20app/pizza-9_b8rshw.jpg',
        ]);

        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'New Orleans Jazz',
            'toppings' => 'Grilled Chicken, Onions, Green Peppers, Garlic Sauce Drizzling & Mozzarella Cheese.',
            'image_url' => 'https://res.cloudinary.com/itorgov/image/upload/v1587129059/Pizza%20Shop%20Vue%20app/pizza-8_ahfr2e.jpg',
        ]);

        factory(Pizza::class, 25)->states('published', 'has_sizes')->create([
            'name' => 'The random pizza',
        ]);
    }
}
