<?php

namespace Stereoqweex\TestDromPackage\Client\Interface;

use Stereoqweex\TestDromPackage\Exception\HttpClientException;

interface HttpClientInterface
{
    /** @throws HttpClientException */
    public function get(string $url, array $options = []): array;

    /** @throws HttpClientException */
    public function post(string $url, array $json, array $options = []): array;

    /** @throws HttpClientException */
    public function put(string $url, array $json, array $options = []): array;
}