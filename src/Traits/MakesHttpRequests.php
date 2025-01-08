<?php

namespace AlvinCoded\MtnMomoAi\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException;
use Illuminate\Support\Facades\Cache;

trait MakesHttpRequests
{
    protected $httpClient;

    protected function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new Client([
                'base_uri' => $this->config['base_url'],
                'timeout'  => $this->config['timeout'] ?? 30,
            ]);
        }

        return $this->httpClient;
    }

    protected function makeRequest($method, $endpoint, $data = [], $headers = [])
    {
        $defaultHeaders = [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'X-Reference-Id' => $this->generateUuid(),
            'X-Target-Environment' => $this->config['environment'],
            'Ocp-Apim-Subscription-Key' => $this->config['subscription_key'],
            'Content-Type' => 'application/json',
        ];

        $mergedHeaders = array_merge($defaultHeaders, $headers);

        try {
            $response = $this->getHttpClient()->request($method, $endpoint, [
                'headers' => $mergedHeaders,
                'json' => $data,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new MtnMomoApiException(
                $e->getMessage(),
                $e->getCode(),
                $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null
            );
        }
    }

    protected function getAccessToken()
    {
        $cacheKey = 'mtn_momo_access_token';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $token = $this->requestNewAccessToken();

        // Cache the token for slightly less than its expiration time (3600 seconds = 1 hour)
        Cache::put($cacheKey, $token, 3540);

        return $token;
    }

    protected function requestNewAccessToken()
    {
        $client = new Client();
        $response = $client->post($this->config['base_url'] . '/collection/token/', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->config['api_user'] . ':' . $this->config['api_key']),
                'Ocp-Apim-Subscription-Key' => $this->config['subscription_key'],
            ],
        ]);

        $body = json_decode($response->getBody(), true);

        if (!isset($body['access_token'])) {
            throw new MtnMomoApiException('Failed to obtain access token');
        }

        return $body['access_token'];
    }

    protected function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
