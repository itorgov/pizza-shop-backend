<?php

namespace Tests\Feature;

use App\Order;
use App\Pizza;
use App\PizzaSize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        setting(['delivery_fee.usd' => 299]);
        setting(['delivery_fee.eur' => 250]);
        setting()->save();
    }

    private function validParams(array $overrides = [])
    {
        return array_merge([
            'name' => 'Unauthorized user',
            'phone' => '+79991112233',
            'address' => 'Test str., 11-22',
            'currency' => 'usd',
            'delivery_fee' => 299,
            'pizza_sizes' => [
                [
                    'id' => 1,
                    'price' => 1199,
                    'quantity' => 2,
                    'crust' => 'traditional',
                ],
            ],
        ], $overrides);
    }

    /** @test */
    public function unauthorized_user_can_create_a_new_order()
    {
        /** @var Pizza $pizza */
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        /** @var PizzaSize $pizzaSizeA */
        $pizzaSizeA = factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);
        /** @var PizzaSize $pizzaSizeB */
        $pizzaSizeB = factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 12,
            'price_usd' => 1499,
            'price_eur' => 1349,
        ]);

        $response = $this->post('/api/orders', [
            'name' => 'Unauthorized user',
            'phone' => '+79991112233',
            'address' => 'Test str., 11-22',
            'currency' => 'usd',
            'delivery_fee' => 299,
            'pizza_sizes' => [
                [
                    'id' => $pizzaSizeA->id,
                    'price' => 1199,
                    'quantity' => 2,
                    'crust' => 'traditional',
                ],
                [
                    'id' => $pizzaSizeB->id,
                    'price' => 1499,
                    'quantity' => 3,
                    'crust' => 'thin',
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertExactJson([
            'data' => [
                'status' => 'ok',
            ],
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'name' => 'Unauthorized user',
            'phone' => '+79991112233',
            'address' => 'Test str., 11-22',
            'currency' => 'usd',
            'delivery_fee' => 299,
        ]);

        $this->assertDatabaseHas('order_pizzas', [
            'order_id' => 1,
            'pizza_size_id' => 1,
            'price' => 1199,
            'quantity' => 2,
            'crust' => 'traditional',
        ]);

        $this->assertDatabaseHas('order_pizzas', [
            'order_id' => 1,
            'pizza_size_id' => 2,
            'price' => 1499,
            'quantity' => 3,
            'crust' => 'thin',
        ]);
    }

    /** @test */
    public function cannot_create_order_without_name()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_without_phone()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'phone' => '',
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'phone',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_without_address()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'address' => '',
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'address',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_without_currency()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => '',
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'currency',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_with_invalid_currency()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => 'invalid_currency',
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'currency',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_without_delivery_fee()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'delivery_fee' => '',
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'delivery_fee',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_when_delivery_fee_not_integer()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'delivery_fee' => 2.99,
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'delivery_fee',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_with_invalid_delivery_fee()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => 'usd',
            'delivery_fee' => 250,
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'delivery_fee',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_without_pizza_sizes()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'pizza_sizes' => '',
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'pizza_sizes',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_without_pizza_size_id()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => 'usd',
            'pizza_sizes' => [
                [
                    'price' => 1199,
                    'quantity' => 2,
                    'crust' => 'traditional',
                ],
            ],
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'pizza_sizes.0.id',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_with_not_existed_pizza_size()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => 'usd',
            'pizza_sizes' => [
                [
                    'id' => 999,
                    'price' => 1199,
                    'quantity' => 2,
                    'crust' => 'traditional',
                ],
            ],
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'pizza_sizes.0.id',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_without_pizza_size_price()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        $pizzaSize = factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => 'usd',
            'pizza_sizes' => [
                [
                    'id' => $pizzaSize->id,
                    'quantity' => 2,
                    'crust' => 'traditional',
                ],
            ],
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'pizza_sizes.0.price',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_with_invalid_pizza_size_price()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        $pizzaSize = factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => 'usd',
            'pizza_sizes' => [
                [
                    'id' => $pizzaSize->id,
                    'price' => 1049, // Price in EUR.
                    'quantity' => 2,
                    'crust' => 'traditional',
                ],
            ],
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'pizza_sizes.0.price',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_without_pizza_size_quantity()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        $pizzaSize = factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => 'usd',
            'pizza_sizes' => [
                [
                    'id' => $pizzaSize->id,
                    'price' => 1199,
                    'crust' => 'traditional',
                ],
            ],
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'pizza_sizes.0.quantity',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_with_pizza_size_quantity_less_then_one()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        $pizzaSize = factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => 'usd',
            'pizza_sizes' => [
                [
                    'id' => $pizzaSize->id,
                    'price' => 1199,
                    'quantity' => 0,
                    'crust' => 'traditional',
                ],
            ],
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'pizza_sizes.0.quantity',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_without_pizza_size_crust()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        $pizzaSize = factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => 'usd',
            'pizza_sizes' => [
                [
                    'id' => $pizzaSize->id,
                    'price' => 1199,
                    'quantity' => 2,
                ],
            ],
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'pizza_sizes.0.crust',
            ],
        ]);
    }

    /** @test */
    public function cannot_create_order_with_invalid_pizza_size_crust()
    {
        $pizza = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        $pizzaSize = factory(PizzaSize::class)->create([
            'pizza_id' => $pizza->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->post('/api/orders', $this->validParams([
            'currency' => 'usd',
            'pizza_sizes' => [
                [
                    'id' => $pizzaSize->id,
                    'price' => 1199,
                    'quantity' => 2,
                    'crust' => 'invalid_crust',
                ],
            ],
        ]));

        $response->assertStatus(422);
        $this->assertEquals(0, Order::query()->count());
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'pizza_sizes.0.crust',
            ],
        ]);
    }
}
