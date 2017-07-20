<?php

namespace Omni\Lead;

use Omni\Lead\Api\LeadApiInterface;

/**
 * Client to use the Omni.Sell Lead API.
 */
interface LeadClientInterface
{
    /**
     * Gets the authentication access token
     *
     * @return null|string
     */
    public function getToken();

    /**
     * Gets the authentication refresh token
     *
     * @return null|string
     */
    public function getRefreshToken();

    /**
     * Gets the product API
     *
     * @return LeadApiInterface
     */
    public function getLeadApi();
}
