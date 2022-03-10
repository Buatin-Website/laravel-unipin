<?php

namespace Buatin\LaravelUnipin;

use Buatin\LaravelUnipin\Facades\Unipin;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

class UnipinInGameTopup
{
    /**
     * Get Game List
     *
     * @return array
     */
    public function getGameList(): array
    {
        try {
            $response = Unipin::request('/in-game-topup/list');

            return $response['game_list'];
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
     * Get Game Detail
     *
     * @param string $gameCode
     * @return array
     */
    public function getGameDetail(string $gameCode): array
    {
        try {
            return Unipin::request('/in-game-topup/detail', [
                'game_code' => $gameCode,
                'description' => true,
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
     * Validate user
     *
     * @param string $gameCode
     * @param array $fields
     * @return array
     */
    public function validateUser(string $gameCode, array $fields = []): array
    {
        try {
            return Unipin::request('/in-game-topup/user/validate', [
                'game_code' => $gameCode,
                'fields' => $fields,
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
     * @param string $gameCode
     * @param string $denomId
     * @param null $token
     * @param array $fields
     * @return array
     */
    public function createOrder(string $referenceNo, string $gameCode, string $denomId, $token = null, array $fields = []): array
    {
        try {
            if (is_null($token)) {
                $validation_token = $this->validateUser($gameCode, $fields);
                if (! $validation_token['status']) {
                    throw new Exception($validation_token['error']['message']);
                }
                $token = $validation_token['validation_token'];
            }

            // TODO: Check inquiry before create order
            return Unipin::request('/in-game-topup/order/create', [
                'game_code' => $gameCode,
                'validation_token' => $token,
                'reference_no' => $referenceNo,
                'denomination_id' => $denomId,
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
     * Get Order Detail
     *
     * @param string $referenceNo
     * @return array|PromiseInterface|Response|mixed
     */
    public function orderInquiry(string $referenceNo): mixed
    {
        try {
            return Unipin::request('/in-game-topup/order/inquiry', [
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
}
