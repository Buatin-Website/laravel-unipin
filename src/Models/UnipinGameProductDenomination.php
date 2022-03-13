<?php

namespace Buatin\LaravelUnipin\Models;

use Buatin\LaravelUnipin\Traits\FormatDates;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnipinGameProductDenomination extends Model
{
    use FormatDates;
    protected $table = 'unipin_game_product_denominations';
    protected $fillable = [
        'game_product_id',
        'denom_id',
        'name',
        'currency',
        'amount',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(UnipinGameProduct::class, 'game_product_id');
    }

    // Accessor & Mutator
    public function code(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->product->game_code . '_' . $this->denom_id;
            },
        );
    }
}
