<?php

namespace App\Http\Resources;

use App\Pizza;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Pizza $resource
 */
class PizzaResource extends JsonResource
{
    /**
     * PizzaResource constructor.
     *
     * @param Pizza $resource
     */
    public function __construct(Pizza $resource)
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
            'toppings' => $this->resource->toppings,
            'image_url' => $this->resource->image_url,
            'sizes' => $this->whenLoaded('sizes', function () {
                return new PizzaSizesResource($this->resource->sizes);
            }),
        ];
    }
}
