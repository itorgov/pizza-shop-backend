<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Orders\StoreRequest;
use App\Http\Resources\OrdersResource;
use App\Order;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index(): OrdersResource
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('orders.pizzas.pizzaSize.pizza');

        return new OrdersResource($user->orders);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        /** @var Order $order */
        $order = Order::query()->create([
            'user_id' => optional(Auth::user())->id,
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
