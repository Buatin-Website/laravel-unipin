<?php

namespace Buatin\LaravelUnipin\Models;

use Buatin\LaravelUnipin\Traits\FormatDates;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnipinGameProduct extends Model
{
    use FormatDates;
    protected $table = 'unipin_game_products';
    protected $fillable = [
        'game_category',
        'game_code',
        'game_name',
        'icon_url',
        'game_status',
        'product_name',
        'category_name',
    ];

    protected $appends = [
        'name',
    ];
    
    // Accessors & Mutators
    public function name(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->game_name . ' (' . $this->game_code . ')';
            },
        );
    }
    
    // Relationships
    public function denominations(): HasMany
    {
        return $this->hasMany(UnipinGameProductDenomination::class, 'game_product_id');
    }

    public function productFields(): HasMany
    {
        return $this->hasMany(UnipinGameProductField::class, 'game_product_id');
    }
}
