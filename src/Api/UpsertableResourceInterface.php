<?php

namespace Omni\Lead\Api;

use Omni\Lead\Exception\HttpException;

/**
 * API that can "upsert" a resource.
 */
interface UpsertableResourceInterface
{
    /**
     * Creates a resource if it does not exist yet, otherwise updates partially the resource.
     *
     * @param string $code code of the resource to create or update
     * @param array  $data data of the resource to create or update
     *
     * @throws HttpException If the request failed.
     *
     * @return int Status code 201 indicating that the resource has been well created.
     *             Status code 204 indicating that the resource has been well updated.
     */
    public function upsert($code, array $data = []);
}
