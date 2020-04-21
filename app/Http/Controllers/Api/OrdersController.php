<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Orders\StoreRequest;
use App\Order;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{
    public function store(StoreRequest $request): JsonResponse
    {
        /** @var Order $order */
        $order = Order::query()->create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'currency' => $request->input('currency'),
            'delivery_fee' => $request->input('delivery_fee'),
        ]);

        foreach ($request->input('pizza_sizes') as $pizzaSize) {
            $order->addPizza(
                $pizzaSize['id'],
                $pizzaSize['price'],
                $pizzaSize['quantity'],
                $pizzaSize['crust'],
            );
        }

        return response()->json([
            'data' => [
                'status' => 'ok',
            ],
        ], JsonResponse::HTTP_CREATED);
    }
}
