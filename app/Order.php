<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $currency
 * @property string $delivery_fee
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @property-read Collection|OrderPizza $pizzas
 */
class Order extends Model
{
    public const CURRENCY_USD = 'usd';
    public const CURRENCY_EUR = 'eur';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
        'currency',
        'delivery_fee',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function addPizza(int $id, int $price, int $quantity, string $crust)
    {
        $this->pizzas()->create([
            'pizza_size_id' => $id,
            'price' => $price,
            'quantity' => $quantity,
            'crust' => $crust,
        ]);
    }

    public function pizzas(): HasMany
    {
        return $this->hasMany(OrderPizza::class);
    }
}
