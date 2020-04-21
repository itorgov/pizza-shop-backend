<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property array $resource
 */
class DeliveryFeeResource extends JsonResource
{
    /**
     * DeliveryFeeResource constructor.
     *
     * @param array $resource
     */
    public function __construct(array $resource)
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
            'usd' => $this->resource['usd'] ?? 0,
            'eur' => $this->resource['eur'] ?? 0,
        ];
    }
}
