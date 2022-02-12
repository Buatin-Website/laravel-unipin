<?php

namespace Buatin\LaravelUnipin;

use Exception;
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
    public function makeRequest(string $path, array $params = [])
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
}
