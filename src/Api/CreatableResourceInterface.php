<?php

namespace Omni\Lead\Api;

use Omni\Lead\Exception\HttpException;
use Omni\Lead\Exception\InvalidArgumentException;

/**
 * API that can create a resource.
 */
interface CreatableResourceInterface
{
    /**
     * Creates a resource.
     *
     * @param string $code code of the resource to create
     * @param array  $data data of the resource to create
     *
     * @throws HttpException            If the request failed.
     * @throws InvalidArgumentException If the parameter "code" is defined in the data parameter.
     *
     * @return int Status code 201 indicating that the resource has been well created.
     */
    public function create(array $data = []);
}
