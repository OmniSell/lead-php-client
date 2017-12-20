<?php

namespace Omni\Lead\Api;

use Omni\Lead\Exception\HttpException;

/**
 * API to manage the leads.
 */
interface OfferApiInterface extends GettableResourceInterface, UpsertableResourceInterface
{
    /**
     * Marks a resource as completed
     *
     * @param string $code Code of the resource
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function complete($code);
}
