<?php

namespace Omni\Lead\Client;

use Http\Client\HttpClient as Client;
use Http\Message\RequestFactory;

/**
 * Http client to send a request without any authentication.
 */
class HttpClient implements HttpClientInterface
{
    /** @var Client */
    protected $httpClient;

    /** @var RequestFactory */
    protected $requestFactory;

    /** @var HttpExceptionHandler */
    protected $httpExceptionHandler;

    /**
     * @param Client         $httpClient
     * @param RequestFactory $requestFactory
     */
    public function __construct(
        Client $httpClient,
        RequestFactory $requestFactory
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->httpExceptionHandler = new HttpExceptionHandler();
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest($httpMethod, $uri, array $headers = [], $body = null)
    {
        $request = $this->requestFactory->createRequest($httpMethod, $uri, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        $response = $this->httpExceptionHandler->transformResponseToException($request, $response);

        return $response;
    }
}
