<?php

namespace App\Http\Resources;

use App\OrderPizza;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property OrderPizza $resource
 */
class OrderPizzaResource extends JsonResource
{
    /**
     * OrderPizzaResource constructor.
     *
     * @param OrderPizza $resource
     */
    public function __construct(OrderPizza $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'pizzaSize' => $this->whenLoaded('pizzaSize', function () {
                return new PizzaSizeResource($this->resource->pizzaSize);
            }),
            'price' => $this->resource->price,
            'quantity' => $this->resource->quantity,
            'crust' => $this->resource->crust,
        ];
    }
}
