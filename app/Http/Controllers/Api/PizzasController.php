<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PizzasResource;
use App\Pizza;

class PizzasController extends Controller
{
    public function index(): PizzasResource
    {
        $pizzas = Pizza::query()
            ->published()
            ->onlyWithSizes()
            ->orderBy('name')
            ->paginate(10);

        return new PizzasResource($pizzas);
    }
}
