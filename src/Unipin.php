<?php

namespace Buatin\LaravelUnipin;

use Buatin\LaravelUnipin\Facades\UnipinInGameTopup;
use Buatin\LaravelUnipin\Models\UnipinGameProduct;
use Buatin\LaravelUnipin\Models\UnipinGameProductDenomination;
use Buatin\LaravelUnipin\Models\UnipinGameProductField;
use Exception;
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
    public function request(string $path, array $params = [])
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

        return hash_hmac('sha256', $api_key . $unix.$path, $api_secret);
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
                        'name' => $denomination['name'],
                    ], [
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
}
