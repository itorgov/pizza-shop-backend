<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryFeeResource;

class DeliveryFeesController extends Controller
{
    public function index(): DeliveryFeeResource
    {
        return new DeliveryFeeResource(setting('delivery_fee'));
    }
}
