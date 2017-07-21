<?php

namespace Omni\Lead\Client;

use Omni\Lead\Exception\HttpException;
use Omni\Lead\Exception\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Generic client interface to execute common request on resources.
 */
interface ResourceClientInterface
{
    /**
     * Gets a resource.
     *
     * @param string $uri             URI of the resource
     * @param array  $uriParameters   URI parameters of the resource
     * @param array  $queryParameters Query parameters of the request
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function getResource($uri, array $uriParameters = [], array $queryParameters = []);

    /**
     * Gets a list of resources.
     *
     * @param string   $uri             URI of the resource
     * @param array    $uriParameters   URI parameters of the resource
     * @param int      $limit           The maximum number of resources to return.
     *                                  Do note that the server has a default value if you don't specify anything.
     *                                  The server has a maximum limit allowed as well.
     * @param bool     $withCount       Set to true to return the total count of resources.
     *                                  This parameter could decrease drastically the performance when set to true.
     * @param array    $queryParameters Additional query parameters of the request
     *
     * @throws InvalidArgumentException If a query parameter is invalid.
     * @throws HttpException            If the request failed.
     *
     * @return array
     */
    public function getResources($uri, array $uriParameters = [], $limit = 10, $withCount = false, array $queryParameters = []);

    /**
     * Creates a resource.
     *
     * @param string $uri           URI of the resource
     * @param array  $uriParameters URI parameters of the resource
     * @param array  $body          Body of the request
     *
     * @throws HttpException If the request failed.
     *
     * @return int Status code 201 indicating that the resource has been well created.
     */
    public function createResource($uri, array $uriParameters = [], array $body = []);

    /**
     * Creates a resource if it does not exist yet, otherwise updates partially the resource.
     *
     * @param string $uri           URI of the resource
     * @param array  $uriParameters URI parameters of the resource
     * @param array  $body          Body of the request
     *
     * @throws HttpException If the request failed.
     *
     * @return int Status code 201 indicating that the resource has been well created.
     *             Status code 204 indicating that the resource has been well updated.
     */
    public function upsertResource($uri, array $uriParameters = [], array $body = []);

    /**
     * Deletes a resource.
     *
     * @param string $uri           URI of the resource to delete
     * @param array  $uriParameters URI parameters of the resource
     *
     * @throws HttpException If the request failed
     *
     * @return int Status code 204 indicating that the resource has been well deleted
     */
    public function deleteResource($uri, array $uriParameters = []);

    /**
     * Gets a streamed resource.
     *
     * @param string $uri           URI of the resource
     * @param array  $uriParameters URI parameters of the resource
     *
     * @throws HttpException If the request failed
     *
     * @return StreamInterface
     */
    public function getStreamedResource($uri, array $uriParameters = []);
}
