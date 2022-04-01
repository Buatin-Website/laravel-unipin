<?php

namespace Buatin\LaravelUnipin\Models;

use Buatin\LaravelUnipin\Traits\FormatDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static updateOrCreate(array $array, array $array1)
 */
class UnipinVoucherProduct extends Model
{
    use FormatDates;

    protected $table = 'unipin_voucher_products';
    protected $fillable = [
        'voucher_name',
        'voucher_code',
        'icon_url',
    ];

    // Relationships
    public function denominations(): HasMany
    {
        return $this->hasMany(UnipinVoucherProductDenomination::class, 'voucher_product_id');
    }
}
