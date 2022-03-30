<?php

namespace Buatin\LaravelUnipin;

use Buatin\LaravelUnipin\Facades\UnipinInGameTopup;
use Buatin\LaravelUnipin\Models\UnipinGameProduct;
use Buatin\LaravelUnipin\Models\UnipinGameProductDenomination;
use Buatin\LaravelUnipin\Models\UnipinGameProductField;
use Buatin\LaravelUnipin\Models\UnipinVoucherProduct;
use Buatin\LaravelUnipin\Models\UnipinVoucherProductDenomination;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Unipin
{
    protected string|null $apiKey;
    protected string|null $secretKey;
    protected int $timestamp;
    protected string $path = '';

    /**
     * @throws Exception
     */
    public function requestGame(string $path, array $params = [])
    {
        $this->apiKey = config('unipin.api_key');
        $this->secretKey = config('unipin.api_secret');
        $this->timestamp = time();
        $path = ltrim($path, '/');
        $this->path = $path;

        $baseUrl = config('unipin.base_url');
        if (! str_ends_with($baseUrl, '/')) {
            $baseUrl .= '/';
        }

        $response = Http::withHeaders([
            'partnerid' => $this->apiKey,
            'timestamp' => $this->timestamp,
            'path' => $path,
            'auth' => $this->authInGameTopup(),
        ])->withOptions([
            'force_ip_resolve' => 'v4',
        ])->post($baseUrl . $path, $params);

        if ($response->failed()) {
            return $response->throw();
        }

        return $response->json();
    }

    public function authInGameTopup(): bool|string
    {
        $api_key = $this->apiKey;
        $api_secret = $this->secretKey;
        $unix = $this->timestamp;
        $path = $this->path;

        return hash_hmac('sha256', $api_key . $unix . $path, $api_secret);
    }

    public function fetchGame(): array
    {
        DB::beginTransaction();

        try {
            $gameList = UnipinInGameTopup::getGameList();
            foreach ($gameList as $game) {
                $gameProduct = UnipinGameProduct::updateOrCreate([
                    'game_code' => $game['game_code'],
                ], [
                    'game_category' => $game['game_category'],
                    'game_name' => $game['game_name'],
                    'icon_url' => $game['icon_url'],
                    'game_status' => $game['game_status'],
                    'product_name' => $game['product_name'],
                    'category_name' => $game['category_name'],
                ]);

                $gameDetail = UnipinInGameTopup::getGameDetail($game['game_code']);
                if (! $gameDetail['status']) {
                    continue;
                }

                foreach ($gameDetail['denominations'] as $denomination) {
                    UnipinGameProductDenomination::updateOrCreate([
                        'game_product_id' => $gameProduct->id,
                        'denom_id' => $denomination['id'],
                    ], [
                        'name' => $denomination['name'],
                        'currency' => $denomination['currency'],
                        'amount' => $denomination['amount'],
                    ]);
                }
                foreach ($gameDetail['fields'] as $field) {
                    UnipinGameProductField::updateOrCreate([
                        'game_product_id' => $gameProduct->id,
                        'name' => $field['name'],
                    ], [
                        'type' => $field['type'],
                        'data' => $field['data'] ?? null,
                    ]);
                }
            }
            DB::commit();

            return [
                'status' => true,
                'message' => 'Successfully updated game list',
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * @throws RequestException
     */
    public function requestVoucher(string $path, string $type, array $queryData = [])
    {
        $this->apiKey = config('unipin.api_key');
        $this->secretKey = config('unipin.api_secret');
        $this->timestamp = time();

        $baseUrl = config('unipin.base_url');
        if (! str_ends_with($baseUrl, '/')) {
            $baseUrl .= '/';
        }

        $data = [
            'partner_guid' => $this->apiKey,
            'logid' => $this->timestamp,
        ];

        switch ($type) {
            case UnipinVoucher::TYPE_LIST:
            case UnipinVoucher::TYPE_STOCK:
                $data['signature'] = hash('sha256', $this->apiKey . $this->timestamp . $this->secretKey);

                break;

            case UnipinVoucher::TYPE_DETAIL:
                $data['signature'] = hash('sha256', $this->apiKey . $this->timestamp . $this->secretKey);
                $data['voucher_code'] = $queryData['voucher_code'];

                break;

            case UnipinVoucher::TYPE_REQUEST:
                $data['signature'] = hash('sha256', $this->apiKey . $queryData['voucher_code'] . $queryData['qty'] . $queryData['reference_no'] . $this->secretKey);
                $data['denomination_code'] = $queryData['voucher_code'];
                $data['quantity'] = $queryData['qty'];
                $data['reference_no'] = $queryData['reference_no'];

                break;

            case UnipinVoucher::TYPE_INQUIRY:
                $data['signature'] = hash('sha256', $this->apiKey . $queryData['reference_no'] . $this->secretKey);
                $data['reference_no'] = $queryData['reference_no'];

                break;

            case UnipinVoucher::TYPE_BALANCE:
                $data['signature'] = hash('sha256', $this->apiKey . $this->secretKey);
                unset($data['logid']);

                break;
        }

        $response = Http::withOptions([
            'force_ip_resolve' => 'v4',
        ])->post($baseUrl . $path, $data);

        if ($response->failed()) {
            return $response->throw();
        }

        return $response->json();
    }

    public function fetchVoucher()
    {
        DB::beginTransaction();

        try {
            $voucherList = \Buatin\LaravelUnipin\Facades\UnipinVoucher::getVoucherList();
            foreach ($voucherList as $voucher) {
                $voucherProduct = UnipinVoucherProduct::updateOrCreate([
                    'voucher_code' => $voucher['voucher_code'],
                ], [
                    'voucher_name' => $voucher['voucher_name'],
                    'icon_url' => $voucher['icon_url'],
                ]);

                $voucherDetail = \Buatin\LaravelUnipin\Facades\UnipinVoucher::getVoucherDetail($voucher['voucher_code']);
                if (! $voucherDetail['status']) {
                    continue;
                }

                foreach ($voucherDetail['denominations'] as $denomination) {
                    UnipinVoucherProductDenomination::updateOrCreate([
                        'voucher_product_id' => $voucherProduct->id,
                        'denomination_code' => $denomination['denomination_code'],
                    ], [
                        'denomination_name' => $denomination['denomination_name'],
                        'denomination_currency' => $denomination['denomination_currency'],
                        'denomination_amount' => $denomination['denomination_amount'],
                    ]);
                }
            }

            DB::commit();

            return [
                'status' => true,
                'message' => 'Successfully updated voucher list',
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
