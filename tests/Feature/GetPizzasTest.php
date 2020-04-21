<?php

namespace Tests\Feature;

use App\Pizza;
use App\PizzaSize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetPizzasTest extends TestCase
{
    use RefreshDatabase;

    private function assertUsingPagination(TestResponse $response)
    {
        $response->assertJsonStructure([
            'data' => [],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);
    }

    /** @test */
    public function user_can_get_published_pizzas()
    {
        $pizzaA = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        $pizzaB = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza B',
            'toppings' => 'Pepper and mozzarella.',
            'image_url' => 'http://example.com/image-b.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizzaA->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizzaA->id,
            'size' => 12,
            'price_usd' => 1499,
            'price_eur' => 1349,
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizzaA->id,
            'size' => 14,
            'price_usd' => 1699,
            'price_eur' => 1549,
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizzaB->id,
            'size' => 12,
            'price_usd' => 1599,
            'price_eur' => 1449,
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizzaB->id,
            'size' => 14,
            'price_usd' => 1799,
            'price_eur' => 1649,
        ]);

        $response = $this->get('/api/pizzas');
        $response->assertStatus(200);
        $this->assertUsingPagination($response);
        $response->assertJson([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Test Pizza A',
                    'toppings' => 'Mushrooms and mozzarella.',
                    'image_url' => 'http://example.com/image-a.jpg',
                    'sizes' => [
                        [
                            'id' => 1,
                            'size' => 10,
                            'price_usd' => 1199,
                            'price_eur' => 1049,
                        ],
                        [
                            'id' => 2,
                            'size' => 12,
                            'price_usd' => 1499,
                            'price_eur' => 1349,
                        ],
                        [
                            'id' => 3,
                            'size' => 14,
                            'price_usd' => 1699,
                            'price_eur' => 1549,
                        ],
                    ],
                ],
                [
                    'id' => 2,
                    'name' => 'Test Pizza B',
                    'toppings' => 'Pepper and mozzarella.',
                    'image_url' => 'http://example.com/image-b.jpg',
                    'sizes' => [
                        [
                            'id' => 4,
                            'size' => 12,
                            'price_usd' => 1599,
                            'price_eur' => 1449,
                        ],
                        [
                            'id' => 5,
                            'size' => 14,
                            'price_usd' => 1799,
                            'price_eur' => 1649,
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function user_cannot_get_unpublished_pizzas()
    {
        factory(Pizza::class)->states('unpublished')->create();

        $response = $this->get('/api/pizzas');

        $response->assertStatus(200);
        $this->assertUsingPagination($response);
        $response->assertJsonFragment([
            'data' => [],
        ]);
        $response->assertJson([
            'meta' => [
                'total' => 0,
            ],
        ]);
    }

    /** @test */
    public function user_cannot_get_pizzas_without_sizes()
    {
        $pizzaA = factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza A',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(Pizza::class)->states('published')->create([
            'name' => 'Test Pizza B',
            'toppings' => 'Pepper and mozzarella.',
            'image_url' => 'http://example.com/image-b.jpg',
        ]);
        factory(PizzaSize::class)->create([
            'pizza_id' => $pizzaA->id,
            'size' => 10,
            'price_usd' => 1199,
            'price_eur' => 1049,
        ]);

        $response = $this->get('/api/pizzas');

        $response->assertStatus(200);
        $this->assertUsingPagination($response);
        $response->assertJson([
            'meta' => [
                'total' => 1,
            ],
        ]);
        $response->assertJsonMissingExact([
            'name' => 'Test Pizza B',
            'toppings' => 'Pepper and mozzarella.',
            'image_url' => 'http://example.com/image-b.jpg',
        ]);
    }

    /** @test */
    public function user_can_view_only_10_pizzas_per_page()
    {
        factory(Pizza::class, 11)->states('published', 'has_sizes')->create();

        $response = $this->get('/api/pizzas');

        $response->assertStatus(200);
        $this->assertUsingPagination($response);
        $this->assertCount(10, $response['data']);
    }

    /** @test */
    public function user_receives_the_first_page_without_the_page_parameter()
    {
        factory(Pizza::class, 30)->states('published')->create();

        $responseWithoutParams = $this->get('/api/pizzas');
        $responseWithParams = $this->get('/api/pizzas?page=1');

        $this->assertEquals($responseWithoutParams->json(), $responseWithParams->json());
    }

    /** @test */
    public function pizzas_should_be_ordered_by_name_asc()
    {
        // Should be in the end of a list, on a second page.
        factory(Pizza::class)->states('published', 'has_sizes')->create([
            'name' => 'Zzzzinger Pizza',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);
        factory(Pizza::class, 10)->states('published', 'has_sizes')->create();

        // The first page. "Zzzzinger Pizza" should be missing.
        $response = $this->get('/api/pizzas');

        $response->assertStatus(200);
        $this->assertUsingPagination($response);
        $response->assertJsonMissingExact([
            'id' => 1,
            'name' => 'Zzzzinger Pizza',
            'toppings' => 'Mushrooms and mozzarella.',
            'image_url' => 'http://example.com/image-a.jpg',
        ]);

        // The second page. "Zzzzinger Pizza" should be present.
        $response = $this->get('/api/pizzas?page=2');

        $response->assertStatus(200);
        $this->assertUsingPagination($response);
        $response->assertJson([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Zzzzinger Pizza',
                    'toppings' => 'Mushrooms and mozzarella.',
                    'image_url' => 'http://example.com/image-a.jpg',
                ],
            ],
        ]);
    }
}
