<?php

namespace Omni\Lead\Client;

use Omni\Lead\Exception\InvalidArgumentException;
use Omni\Lead\Routing\UriGeneratorInterface;

/**
 * Generic client to execute common request on resources.
 */
class ResourceClient implements ResourceClientInterface
{
    /** @var HttpClientInterface */
    protected $httpClient;

    /** @var UriGeneratorInterface */
    protected $uriGenerator;

    /**
     * @param HttpClientInterface               $httpClient
     * @param UriGeneratorInterface             $uriGenerator
     */
    public function __construct(
        HttpClientInterface $httpClient,
        UriGeneratorInterface $uriGenerator
    ) {
        $this->httpClient = $httpClient;
        $this->uriGenerator = $uriGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function getResource($uri, array $uriParameters = [], array $queryParameters = [])
    {
        $uri = $this->uriGenerator->generate($uri, $uriParameters, $queryParameters);
        $response = $this->httpClient->sendRequest('GET', $uri, ['Accept' => '*/*']);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function getResources(
        $uri,
        array $uriParameters = [],
        $limit = 10,
        $withCount = false,
        array $queryParameters = []
    ) {
        if (array_key_exists('limit', $queryParameters)) {
            throw new InvalidArgumentException('The parameter "limit" should not be defined in the additional query parameters');
        }

        if (array_key_exists('with_count', $queryParameters)) {
            throw new InvalidArgumentException('The parameter "with_count" should not be defined in the additional query parameters');
        }

        if (null !== $limit) {
            $queryParameters['limit'] = $limit;
        }

        if (null !== $withCount) {
            $queryParameters['with_count'] = $withCount;
        }

        return $this->getResource($uri, $uriParameters, $queryParameters);
    }

    /**
     * {@inheritdoc}
     */
    public function createResource($uri, array $uriParameters = [], array $body = [])
    {
        unset($body['_links']);

        $uri = $this->uriGenerator->generate($uri, $uriParameters);
        $response = $this->httpClient->sendRequest(
            'POST',
            $uri,
            ['Content-Type' => 'application/json'],
            json_encode($body)
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertResource($uri, array $uriParameters = [], array $body = [])
    {
        unset($body['_links']);

        $uri = $this->uriGenerator->generate($uri, $uriParameters);
        $response = $this->httpClient->sendRequest(
            'PATCH',
            $uri,
            ['Content-Type' => 'application/json'],
            json_encode($body)
        );

        return $response->getStatusCode();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteResource($uri, array $uriParameters = [])
    {
        $uri = $this->uriGenerator->generate($uri, $uriParameters);

        $response = $this->httpClient->sendRequest('DELETE', $uri);

        return $response->getStatusCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getStreamedResource($uri, array $uriParameters = [])
    {
        $uri = $this->uriGenerator->generate($uri, $uriParameters);
        $response = $this->httpClient->sendRequest('GET', $uri, ['Accept' => '*/*']);

        return $response->getBody();
    }
}
