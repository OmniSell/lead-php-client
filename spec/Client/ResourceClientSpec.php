<?php

namespace spec\Omni\Lead\Client;

use Omni\Lead\Client\ResourceClient;
use Omni\Lead\Client\ResourceClientInterface;
use Omni\Lead\Exception\InvalidArgumentException;
use Omni\Lead\Client\HttpClient;
use Omni\Lead\Routing\UriGeneratorInterface;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResourceClientSpec extends ObjectBehavior
{
    const RESOURCE_JSON = <<<JSON
{
    "code": "winter_collection",
    "parent": null,
    "labels": {
        "en_US": "Winter collection",
        "fr_FR": "Collection hiver"
    }
}
JSON;

    function let(
        HttpClient $httpClient,
        UriGeneratorInterface $uriGenerator
    ) {
        $this->beConstructedWith($httpClient, $uriGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(ResourceClientInterface::class);
        $this->shouldHaveType(ResourceClient::class);
    }

    function it_gets_resource($httpClient, $uriGenerator, ResponseInterface $response, StreamInterface $responseBody)
    {
        $uri = 'http://omnisell.com/api/rest/v1/categories/winter_collection';
        $resource =
<<<JSON
{
    "code": "winter_collection",
    "parent": null,
    "labels": {
        "en_US": "Winter collection",
        "fr_FR": "Collection hiver"
    }
}
JSON;

        $uriGenerator
            ->generate('api/rest/v1/categories/%s', ['winter_collection'], [])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('GET', $uri, ['Accept' => '*/*'])
            ->willReturn($response);

        $response
            ->getBody()
            ->willReturn($responseBody);

        $responseBody
            ->getContents()
            ->willReturn($resource);

        $this->getResource('api/rest/v1/categories/%s', ['winter_collection'], [])->shouldReturn([
            'code' => 'winter_collection',
            'parent' => null,
            'labels' => [
                'en_US' => 'Winter collection',
                'fr_FR' => 'Collection hiver',
            ],
        ]);
    }

    function it_returns_a_page_when_requesting_a_list_of_resources(
        $httpClient,
        $uriGenerator,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $uri = 'http://omnisell.com/api/rest/v1/categories?limit=10&with_count=15&foo=bar';
        $resources = $this->getSampleOfResources();

        $uriGenerator
            ->generate('api/rest/v1/categories', [], ['foo' => 'bar', 'limit' => 10, 'with_count' => true])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('GET', $uri, ['Accept' => '*/*'])
            ->willReturn($response);

        $response
            ->getBody()
            ->willReturn($responseBody);

        $responseBody
            ->getContents()
            ->willReturn(json_encode($resources));

        $this->getResources('api/rest/v1/categories', [], 10, true, ['foo' => 'bar'])->shouldReturn($resources);
    }

    function it_returns_a_list_of_resources_without_limit_and_count(
        $httpClient,
        $uriGenerator,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $uri = 'http://omnisell.com/api/rest/v1/categories?foo=bar';
        $resources = $this->getSampleOfResources();

        $uriGenerator
            ->generate('api/rest/v1/categories', [], ['foo' => 'bar'])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('GET', $uri, ['Accept' => '*/*'])
            ->willReturn($response);

        $response
            ->getBody()
            ->willReturn($responseBody);

        $responseBody
            ->getContents()
            ->willReturn(json_encode($resources));

        $this->getResources('api/rest/v1/categories', [], null, null, ['foo' => 'bar'])->shouldReturn($resources);
    }

    function it_creates_a_resource(
        $httpClient,
        $uriGenerator,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $uri = 'http://omnisell.com/api/rest/v1/categories';

        $uriGenerator
            ->generate('api/rest/v1/categories', [])
            ->willReturn($uri);

        $responseBody
            ->getContents()
            ->willReturn(self::RESOURCE_JSON);

        $response
            ->getBody()
            ->willReturn($responseBody);

        $httpClient
            ->sendRequest('POST', $uri, ['Content-Type' => 'application/json'], '{"code":"master"}')
            ->willReturn($response);

        $this->createResource(
            'api/rest/v1/categories',
            [],
            [
                '_links' => [
                    'self' => [
                        'href' => 'http://omnisell.com/self',
                    ],
                ],
                'code'   => 'master',
            ]
        );
    }

    function it_upserts_a_resource(
        $httpClient,
        $uriGenerator,
        ResponseInterface $response
    ) {
        $uri = 'http://omnisell.com/api/rest/v1/categories/master';

        $uriGenerator
            ->generate('api/rest/v1/categories/%s', ['master'])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('PATCH', $uri, ['Content-Type' => 'application/json'], '{"parent":"foo"}')
            ->willReturn($response);

        $response
            ->getStatusCode()
            ->willReturn(201);

        $this
            ->upsertResource(
                'api/rest/v1/categories/%s',
                ['master'],
                [
                    '_links' => [
                        'self' => [
                            'href' => 'http://omnisell.com/self',
                        ],
                    ],
                    'parent' => 'foo'
                ]
            )
            ->shouldReturn(201);
    }

    function it_throws_an_exception_if_limit_is_defined_in_additional_parameters_to_get_resources()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('The parameter "limit" should not be defined in the additional query parameters'))
            ->during('getResources', ['', [], null, null, ['limit' => null]]);
    }

    function it_throws_an_exception_if_with_count_is_defined_in_additional_parameters_to_get_resources()
    {
        $this
            ->shouldthrow(new InvalidArgumentException('The parameter "with_count" should not be defined in the additional query parameters'))
            ->during('getResources', ['', [], null, null, ['with_count' => null]]);
    }

    function it_deletes_a_resource(
        $httpClient,
        $uriGenerator,
        ResponseInterface $response
    ) {
        $uri = 'api/rest/v1/products/foo';

        $uriGenerator
            ->generate('api/rest/v1/products/%s', ['foo'])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('DELETE', $uri)
            ->willReturn($response);

        $response
            ->getStatusCode()
            ->willReturn(204);

        $this
            ->deleteResource('api/rest/v1/products/%s', ['foo'])
            ->shouldReturn(204);
    }

    protected function getSampleOfResources()
    {
        return [
            '_links'      => [
                'self'     => [
                    'href' => 'http://omnisell.com/self',
                ],
                'first'    => [
                    'href' => 'http://omnisell.com/first',
                ],
                'previous' => [
                    'href' => 'http://omnisell.com/previous',
                ],
                'next'     => [
                    'href' => 'http://omnisell.com/next',
                ],
            ],
            'items_count' => 10,
            '_embedded'   => [
                'items' => [
                    ['identifier' => 'foo'],
                    ['identifier' => 'bar'],
                ],
            ],
        ];
    }
}
