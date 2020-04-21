<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetDeliveryFeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_get_delivery_fees()
    {
        setting(['delivery_fee.usd' => 299]);
        setting(['delivery_fee.eur' => 250]);
        setting()->save();

        $response = $this->get('/api/delivery-fees');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'usd' => 299,
                'eur' => 250,
            ],
        ]);
    }

    /** @test */
    public function user_receives_zero_when_delivery_fees_not_set()
    {
        setting(['delivery_fee.usd' => 299]);
        setting()->save();

        $response = $this->get('/api/delivery-fees');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'usd' => 299,
                'eur' => 0,
            ],
        ]);
    }
}
