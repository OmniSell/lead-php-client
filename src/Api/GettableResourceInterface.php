<?php

namespace Omni\Lead\Api;

use Omni\Lead\Exception\HttpException;

/**
 * API that can fetch a single resource.
 */
interface GettableResourceInterface
{
    /**
     * Gets a resource by its code
     *
     * @param string $code Code of the resource
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function get($code);
}
