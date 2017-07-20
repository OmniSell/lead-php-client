<?php

namespace Omni\Lead\Client;

use Omni\Lead\Api\AuthenticationApiInterface;
use Omni\Lead\Exception\UnauthorizedHttpException;
use Omni\Lead\Security\Authentication;

/**
 * Http client to send an authenticated request.
 *
 * The authentication process is automatically handle by this client implementation.
 *
 * It enriches the request with an access token.
 * If the access token is expired, it will automatically refresh it.
 */
class AuthenticatedHttpClient implements HttpClientInterface
{
    /** @const string */
    const API_VERSION = 'v1';

    /** @var HttpClient */
    protected $basicHttpClient;

    /** @var AuthenticationApiInterface */
    protected $authenticationApi;

    /** @var Authentication */
    protected $authentication;

    /**
     * @param HttpClient                 $basicHttpClient
     * @param AuthenticationApiInterface $authenticationApi
     * @param Authentication             $authentication
     */
    public function __construct(
        HttpClient $basicHttpClient,
        AuthenticationApiInterface $authenticationApi,
        Authentication $authentication
    ) {
        $this->basicHttpClient = $basicHttpClient;
        $this->authenticationApi = $authenticationApi;
        $this->authentication = $authentication;
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest($httpMethod, $uri, array $headers = [], $body = null)
    {
        if (null === $this->authentication->getAccessToken()) {
            $tokens = $this->authenticationApi->authenticateByPassword(
                $this->authentication->getClientId(),
                $this->authentication->getSecret(),
                $this->authentication->getUsername(),
                $this->authentication->getPassword()
            );

            $this->authentication
                ->setAccessToken($tokens['access_token'])
                ->setRefreshToken($tokens['refresh_token']);
        }

        try {
            $headers['Authorization'] =  sprintf('Bearer %s', $this->authentication->getAccessToken());
            $headers['X-Accept-Version'] = self::API_VERSION;
            $response = $this->basicHttpClient->sendRequest($httpMethod, $uri, $headers, $body);
        } catch (UnauthorizedHttpException $e) {
            $tokens = $this->authenticationApi->authenticateByRefreshToken(
                $this->authentication->getClientId(),
                $this->authentication->getSecret(),
                $this->authentication->getRefreshToken()
            );

            $this->authentication
                ->setAccessToken($tokens['access_token'])
                ->setRefreshToken($tokens['refresh_token']);

            $headers['Authorization'] =  sprintf('Bearer %s', $this->authentication->getAccessToken());
            $response =  $this->basicHttpClient->sendRequest($httpMethod, $uri, $headers, $body);
        }

        return $response;
    }
}
