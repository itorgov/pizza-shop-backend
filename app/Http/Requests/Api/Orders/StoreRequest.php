<?php

namespace App\Http\Requests\Api\Orders;

use App\Order;
use App\PizzaSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
            ],
            'phone' => [
                'required',
                'string',
            ],
            'address' => [
                'required',
                'string',
            ],
            'currency' => [
                'required',
                'string',
                Rule::in([
                    Order::CURRENCY_USD,
                    Order::CURRENCY_EUR,
                ]),
            ],
            'delivery_fee' => [
                'required',
                'integer',
                Rule::in([
                    setting("delivery_fee.{$this->input('currency')}"),
                ]),
            ],
            'pizza_sizes' => [
                'required',
                'array',
                'filled',
            ],
            'pizza_sizes.*.id' => [
                'required',
                'integer',
                Rule::exists('pizza_sizes', 'id'),
            ],
            'pizza_sizes.*.price' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $idInputKey = Str::replaceLast('price', 'id', $attribute);
                    $rightPriceColumn = "price_{$this->input('currency')}";

                    if (! PizzaSize::query()
                        ->where('id', $this->input($idInputKey))
                        ->where($rightPriceColumn, $value)
                        ->exists()) {
                        $fail($attribute.' has different value in a database.');
                    }
                },
            ],
            'pizza_sizes.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'pizza_sizes.*.crust' => [
                'required',
                'string',
                Rule::in([
                    PizzaSize::CRUST_TRADITIONAL,
                    PizzaSize::CRUST_THIN,
                ]),
            ],
        ];
    }
}
