<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $pizza_id
 * @property int $size Diameter in inches.
 * @property int $price_usd Price in US$ times 100.
 * @property int $price_eur Price in Euros times 100.
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @property-read Pizza $pizza
 */
class PizzaSize extends Model
{
    public const CRUST_TRADITIONAL = 'traditional';
    public const CRUST_THIN = 'thin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pizza_id',
        'size',
        'price_usd',
        'price_eur',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pizza_id' => 'integer',
        'size' => 'integer',
        'price_usd' => 'integer',
        'price_eur' => 'integer',
    ];

    public function pizza(): BelongsTo
    {
        return $this->belongsTo(Pizza::class);
    }
}
