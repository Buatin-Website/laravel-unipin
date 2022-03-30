<?php

use Buatin\LaravelUnipin\Facades\UnipinVoucher;
use function Pest\Faker\faker;

beforeAll(function () {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
});

it('can get all voucher', function () {
    $voucherList = UnipinVoucher::getVoucherList();

    expect($voucherList)->toBeArray()->toHaveKey(0);
});

it('can get voucher stock', function () {
    $voucherStock = UnipinVoucher::getVoucherStock();

    expect($voucherStock)->toBeArray();
});

it('can\'t get voucher detail (not found)', function () {
    $voucherStock = UnipinVoucher::getVoucherDetail(faker()->slug);

    expect($voucherStock)->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Invalid Voucher Code',
            'error_code' => 704,
        ],
    ]);
});

it('can get voucher detail', function () {
    $voucherStock = UnipinVoucher::getVoucherDetail('POD_GSID');

    expect($voucherStock)->toBeArray()->toMatchArray([
        'status' => true,
    ]);
});

it('can\'t create order (Invalid Voucher Code)', function () {
    $createOrder = UnipinVoucher::createOrder(
        faker()->uuid,
        faker()->slug(),
        faker()->numberBetween(1, 10),
    );

    expect($createOrder)->toBeArray()->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Item not found',
            'error_code' => 707,
        ],
    ]);
});

it('can\'t create order (Out Of Stock)', function () {
    $createOrder = UnipinVoucher::createOrder(
        faker()->uuid,
        'POD_GSID002',
        10,
    );

    expect($createOrder)->toBeArray()->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Out Of Stock',
            'error_code' => 705,
        ],
    ]);
});

it('can create order (Invalid Voucher Code)', function () {
    $createOrder = UnipinVoucher::createOrder(
        faker()->uuid,
        'POD_GSID001',
        1,
    );

    expect($createOrder)->toBeArray()->toMatchArray([
        'status' => true,
    ]);
});

it('can\'t get order inquiry (transaction not found)', function () {
    $orderInquiry = UnipinVoucher::orderInquiry(faker()->uuid);

    expect($orderInquiry)->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Transaction not found',
            'error_code' => 716,
        ],
    ]);
});

it('can get order inquiry', function () {
    $orderInquiry = UnipinVoucher::orderInquiry(57395);

    expect($orderInquiry)->toBeArray()->toMatchArray([
        'status' => true,
    ]);
});

it('can get balance inquiry', function () {
    $orderInquiry = UnipinVoucher::balanceInquiry();

    expect($orderInquiry)->toBeArray()->toMatchArray([
        'status' => true,
    ]);
});
