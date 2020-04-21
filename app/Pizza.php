<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $toppings
 * @property string $image_url
 * @property null|Carbon $published_at
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @property-read Collection|PizzaSize[] $sizes
 */
class Pizza extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'toppings',
        'image_url',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at',
    ];

    /**
     * Only published pizzas.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * Only pizzas with sizes.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnlyWithSizes(Builder $query): Builder
    {
        return $query->whereHas('sizes')->with('sizes');
    }

    /**
     * @return HasMany
     */
    public function sizes(): HasMany
    {
        return $this->hasMany(PizzaSize::class);
    }
}
