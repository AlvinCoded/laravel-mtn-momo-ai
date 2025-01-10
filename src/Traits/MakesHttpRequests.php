<?php

namespace AlvinCoded\MtnMomoAi\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException;
use Illuminate\Support\Facades\Cache;

/**
 * Trait MakesHttpRequests
 * 
 * Handles HTTP communication with the MTN MOMO API, including authentication,
 * token management, and request handling.
 *
 * @package AlvinCoded\MtnMomoAi\Traits
 */
trait MakesHttpRequests
{
    /** @var Client */
    protected $httpClient;

    /**
     * Get or create a configured HTTP client instance.
     *
     * @return Client
     */
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

    /**
     * Make an HTTP request to the MTN MOMO API.
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $endpoint API endpoint path
     * @param array $data Request body data
     * @param array $headers Additional headers
     * @return array Decoded response
     * @throws MtnMomoApiException When the API request fails
     */
    protected function makeRequest($method, $endpoint, $data = [], $headers = [])
    {
        $defaultHeaders = [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'X-Reference-Id' => $this->config['api_user'],
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

    /**
     * Get a valid access token, either from cache or by requesting a new one.
     *
     * The MTN MOMO API access tokens are valid for 1 hour. This method implements
     * caching to avoid requesting new tokens unnecessarily.
     *
     * @return string Valid access token
     * @throws MtnMomoApiException When token retrieval fails
     */
    protected function getAccessToken()
    {
        $cacheKey = 'mtn_momo_access_token';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $token = $this->requestNewAccessToken();

        // Cache for 59 minutes (3540 seconds) to ensure token refresh before expiration
        Cache::put($cacheKey, $token, 3540);

        return $token;
    }

    /**
     * Request a new access token from the MTN MOMO API.
     *
     * This method handles the OAuth 2.0 client credentials flow to obtain
     * a new access token using the API user credentials.
     *
     * @return string New access token
     * @throws MtnMomoApiException When token request fails
     */
    protected function requestNewAccessToken()
    {
        $client = new Client();
        try {
            $response = $client->post($this->config['base_url'] . '/collection/token/', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->config['api_user'] . ':' . $this->config['api_key']),
                    'Ocp-Apim-Subscription-Key' => $this->config['subscription_key'],
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if (!isset($body['access_token'])) {
                throw new MtnMomoApiException('Failed to obtain access token: Invalid response format');
            }

            return $body['access_token'];
        } catch (RequestException $e) {
            throw new MtnMomoApiException(
                'Failed to obtain access token: ' . $e->getMessage(),
                $e->getCode(),
                $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null
            );
        }
    }

    /**
     * Generate a UUID v4.
     *
     * Note: This method is kept for backward compatibility or custom UUID generation needs.
     * For API requests, we now use the stored API user UUID instead.
     *
     * @return string UUID v4
     * @deprecated Use stored API user UUID instead for API requests
     */
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
