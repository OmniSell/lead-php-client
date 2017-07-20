<?php

namespace Omni\Lead\Api;

/**
 * API to manage the authentication.
 */
interface AuthenticationApiInterface
{
    /**
     * Authenticates with the password grant type.
     *
     * @param string $clientId
     * @param string $secret
     * @param string $username
     * @param string $password
     *
     * @return array
     */
    public function authenticateByPassword($clientId, $secret, $username, $password);

    /**
     * Authenticates with the refresh token grant type.
     *
     * @param string $clientId
     * @param string $secret
     * @param string $refreshToken
     *
     * @return array
     */
    public function authenticateByRefreshToken($clientId, $secret, $refreshToken);
}
