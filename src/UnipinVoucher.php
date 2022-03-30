<?php

namespace Buatin\LaravelUnipin;

use Buatin\LaravelUnipin\Facades\Unipin;
use Exception;

class UnipinVoucher
{
    public const TYPE_LIST = 'list';
    public const TYPE_STOCK = 'stock';
    public const TYPE_DETAIL = 'detail';
    public const TYPE_REQUEST = 'request';
    public const TYPE_INQUIRY = 'inquiry';
    public const TYPE_BALANCE = 'balance';

    /**
     * Get Voucher List
     *
     * @return array
     */
    public function getVoucherList(): array
    {
        try {
            $response = Unipin::requestVoucher('/voucher/list', self::TYPE_LIST);

            return $response['voucher_list'];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }
    }

    /**
     * Get Voucher Stock
     *
     * @return array
     */
    public function getVoucherStock(): array
    {
        try {
            return Unipin::requestVoucher('/voucher/get_stock_count', self::TYPE_STOCK);
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }
    }

    /**
     * Get Voucher Detail
     *
     * @param string $voucher_code
     * @return array
     */
    public function getVoucherDetail(string $voucher_code): array
    {
        try {
            return Unipin::requestVoucher('/voucher/details', self::TYPE_DETAIL, [
                'voucher_code' => $voucher_code,
            ]);
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }
    }

    /**
     * Create Order
     *
     * @param string $referenceNo
     * @param string $voucherCode
     * @param int $qty
     * @return array
     */
    public function createOrder(string $referenceNo, string $voucherCode, int $qty): array
    {
        try {
            return Unipin::requestVoucher('/voucher/request', self::TYPE_REQUEST, [
                'reference_no' => $referenceNo,
                'voucher_code' => $voucherCode,
                'qty' => $qty,
            ]);
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }
    }

    /**
     * Order Inquiry
     *
     * @param string $referenceNo
     * @return array
     */
    public function orderInquiry(string $referenceNo): array
    {
        try {
            return Unipin::requestVoucher('/voucher/inquiry', self::TYPE_INQUIRY, [
                'reference_no' => $referenceNo,
            ]);
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }
    }

    /**
     * Balance Inquiry
     *
     * @return array
     */
    public function balanceInquiry(): array
    {
        try {
            return Unipin::requestVoucher('/voucher/balance', self::TYPE_BALANCE);
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }
    }
}
