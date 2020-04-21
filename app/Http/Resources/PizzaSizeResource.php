<?php

namespace App\Http\Resources;

use App\PizzaSize;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property PizzaSize $resource
 */
class PizzaSizeResource extends JsonResource
{
    /**
     * PizzaSizeResource constructor.
     *
     * @param PizzaSize $resource
     */
    public function __construct(PizzaSize $resource)
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
            'size' => $this->resource->size,
            'price_usd' => $this->resource->price_usd,
            'price_eur' => $this->resource->price_eur,
        ];
    }
}
