<?php

namespace Omni\Lead\Client;

use Omni\Lead\Exception\HttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Http client interface aims to send a request.
 */
interface HttpClientInterface
{
    /**
     * Sends a request.
     *
     * @param string                      $httpMethod HTTP method to use
     * @param string|UriInterface         $uri        URI of the request
     * @param array                       $headers    headers of the request
     * @param string|StreamInterface|null $body       body of the request
     *
     * @throws HttpException If the request failed.
     *
     * @return ResponseInterface
     */
    public function sendRequest($httpMethod, $uri, array $headers = [], $body = null);
}
