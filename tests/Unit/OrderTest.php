<?php

namespace Tests\Unit;

use App\Order;
use App\PizzaSize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_pizza_to_order()
    {
        /** @var PizzaSize $pizzaSize */
        $pizzaSize = factory(PizzaSize::class)->create();
        /** @var Order $order */
        $order = factory(Order::class)->create();

        $order->addPizza($pizzaSize->id, 1122, 3, PizzaSize::CRUST_THIN);

        $this->assertEquals(1, $order->fresh()->pizzas()->count());
        $this->assertNotNull($order->fresh()->pizzas()->find($pizzaSize->id));
    }
}
