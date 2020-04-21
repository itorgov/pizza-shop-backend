<?php

namespace App\Http\Resources;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Order $resource
 */
class OrderResource extends JsonResource
{
    /**
     * OrderResource constructor.
     *
     * @param Order $resource
     */
    public function __construct(Order $resource)
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
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'address' => $this->resource->address,
            'currency' => $this->resource->currency,
            'delivery_fee' => $this->resource->delivery_fee,
            'pizzas' => $this->whenLoaded('pizzas', function () {
                return new OrderPizzasResource($this->resource->pizzas);
            }),
        ];
    }
}
