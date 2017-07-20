<?php

namespace Omni\Lead\Routing;

use Psr\Http\Message\UriInterface;

/**
 * Interface to generate a complete uri from a base path, uri parameters, and query parameters.
 */
interface UriGeneratorInterface
{
    /**
     * Generate an uri from a path, by adding host and port.
     *
     * @param string $path            Path of the endpoint
     * @param array  $uriParameters   List of the parameters to generate the endpoint
     * @param array  $queryParameters List of the query parameters added to the endpoint
     *
     * @return UriInterface
     */
    public function generate($path, array $uriParameters = [], array $queryParameters = []);
}
