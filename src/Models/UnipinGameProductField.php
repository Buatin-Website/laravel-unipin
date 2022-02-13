<?php

namespace Buatin\LaravelUnipin\Models;

use Buatin\LaravelUnipin\Traits\FormatDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnipinGameProductField extends Model
{
    use FormatDates;
    protected $table = 'unipin_game_product_fields';
    protected $fillable = [
        'game_product_id',
        'name',
        'type',
        'data',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    public function denomination(): BelongsTo
    {
        return $this->belongsTo(UnipinGameProductDenomination::class, 'game_product_denom_id');
    }
}