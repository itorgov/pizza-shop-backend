<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $order_id
 * @property int $pizza_size_id
 * @property int $price
 * @property int $quantity
 * @property string $crust
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @property-read PizzaSize $pizzaSize
 */
class OrderPizza extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'pizza_size_id',
        'price',
        'quantity',
        'crust',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'pizza_size_id' => 'integer',
        'price' => 'integer',
        'quantity' => 'integer',
    ];

    public function pizzaSize(): BelongsTo
    {
        return $this->belongsTo(PizzaSize::class);
    }
}
