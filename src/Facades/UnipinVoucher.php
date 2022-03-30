<?php

namespace Buatin\LaravelUnipin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getVoucherList()
 * @method static getVoucherStock()
 * @method static getVoucherDetail(string $string)
 * @method static createOrder(string $referenceNo, string $voucherCode, int $qty)
 * @method static orderInquiry(string $referenceNo)
 * @method static balanceInquiry()
 */
class UnipinVoucher extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'unipin-voucher';
    }
}
