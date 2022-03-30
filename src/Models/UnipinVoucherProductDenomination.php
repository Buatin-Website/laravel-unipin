<?php

namespace Buatin\LaravelUnipin\Models;

use Buatin\LaravelUnipin\Traits\FormatDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static updateOrCreate(array $array, array $array1)
 */
class UnipinVoucherProductDenomination extends Model
{
    use FormatDates;

    protected $table = 'unipin_voucher_product_denominations';
    protected $fillable = [
        'voucher_product_id',
        'denomination_code',
        'denomination_name',
        'denomination_currency',
        'denomination_amount',
    ];

    // Relationships

    public function product(): BelongsTo
    {
        return $this->belongsTo(UnipinVoucherProduct::class, 'voucher_product_id');
    }
}