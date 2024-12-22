<?php

namespace Stereoqweex\TestDromPackage\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Stereoqweex\TestDromPackage\Client\Interface\HttpClientInterface;
use Stereoqweex\TestDromPackage\Exception\HttpClientException;

class BaseHttpClient implements HttpClientInterface
{
    private Client $client;

    public function __construct(array $config = ['base_uri' => 'https://example.com'])
    {
        $this->client = new Client($config);
    }

    /**
     * @throws HttpClientException
     */
    public function get(string $url, array $options = []): array
    {
        try {
            $response = $this->client->get($url, $options);
            return json_decode(
                json: $response->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (GuzzleException|JsonException $e) {
            throw new HttpClientException($e->getMessage());
        }
    }

    /**
     * @throws HttpClientException
     */
    public function post(string $url, array $json, array $options = []): array
    {
        try {
            $response = $this->client->post($url, array_merge(['json' => $json], $options));
            return json_decode(
                json: $response->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (GuzzleException|JsonException $e) {
            throw new HttpClientException($e->getMessage());
        }
    }

    /**
     * @throws HttpClientException
     */
    public function put(string $url, array $json, array $options = []): array
    {
        try {
            $response = $this->client->put($url, array_merge(['json' => $json], $options));
            return json_decode(
                json: $response->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (GuzzleException|JsonException $e) {
            throw new HttpClientException($e->getMessage());
        }
    }
}