<?php

namespace Buatin\LaravelUnipin;

use Buatin\LaravelUnipin\Facades\Unipin;
use Exception;

class UnipinInGameTopup
{
    /**
     * Get Game List
     *
     * @return mixed|string
     */
    public function getGameList(): mixed
    {
        try {
            $response = Unipin::makeRequest('/in-game-topup/list');

            return $response['game_list'];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Get Game Detail
     *
     * @param string $gameCode
     * @return mixed
     */
    public function getGameDetail(string $gameCode): mixed
    {
        try {
            return Unipin::makeRequest('/in-game-topup/detail', [
                'game_code' => $gameCode,
                'description' => true,
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Validate user
     */
    public function validateUser(string $gameCode, array $fields): mixed
    {
        try {
            return Unipin::makeRequest('/in-game-topup/user/validate', [
                'game_code' => $gameCode,
                'fields' => $fields,
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
