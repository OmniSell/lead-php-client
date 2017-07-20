<?php

namespace Omni\Lead;

use Omni\Lead\Api\AuthenticationApi;
use Omni\Lead\Api\LeadApi;
use Omni\Lead\Api\OfferApi;
use Omni\Lead\Client\AuthenticatedHttpClient;
use Omni\Lead\Client\HttpClient;
use Omni\Lead\Client\ResourceClient;
use Omni\Lead\Routing\UriGenerator;
use Omni\Lead\Security\Authentication;
use Http\Client\HttpClient as Client;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;

/**
 * Builder of the class LeadClient.
 * This builder is in charge to instantiate and inject the dependencies.
 */
class LeadClientBuilder
{
    /** @var string */
    protected $baseUri;

    /** @var Client */
    protected $httpClient;

    /** @var RequestFactory */
    protected $requestFactory;

    /** @var StreamFactory */
    protected $streamFactory;

    /**
     * @param string $baseUri Base uri to request the API
     */
    public function __construct($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @param Client $httpClient
     *
     * @return LeadClientBuilder
     */
    public function setHttpClient(Client $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @param RequestFactory $requestFactory
     *
     * @return LeadClientBuilder
     */
    public function setRequestFactory($requestFactory)
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    /**
     * @param StreamFactory $streamFactory
     *
     * @return LeadClientBuilder
     */
    public function setStreamFactory($streamFactory)
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }

    /**
     * Build the Omni Lead client authenticated by user name and password.
     *
     * @param string $clientId Client id to use for the authentication
     * @param string $secret   Secret associated to the client
     * @param string $username Username to use for the authentication
     * @param string $password Password associated to the username
     *
     * @return LeadClientInterface
     */
    public function buildAuthenticatedByPassword($clientId, $secret, $username, $password)
    {
        $authentication = Authentication::fromPassword($clientId, $secret, $username, $password);

        return $this->buildAuthenticatedClient($authentication);
    }

    /**
     * Build the Omni Lead client authenticated by token.
     *
     * @param string $clientId     Client id to use for the authentication
     * @param string $secret       Secret associated to the client
     * @param string $token        Token to use for the authentication
     * @param string $refreshToken Token to use to refresh the access token
     *
     * @return LeadClientInterface
     */
    public function buildAuthenticatedByToken($clientId, $secret, $token, $refreshToken)
    {
        $authentication = Authentication::fromToken($clientId, $secret, $token, $refreshToken);

        return $this->buildAuthenticatedClient($authentication);
    }

    /**
     * @param Authentication $authentication
     *
     * @return LeadClientInterface
     */
    protected function buildAuthenticatedClient(Authentication $authentication)
    {
        $uriGenerator = new UriGenerator($this->baseUri);

        $httpClient = new HttpClient($this->getHttpClient(), $this->getRequestFactory());
        $authenticationApi = new AuthenticationApi($httpClient, $uriGenerator);
        $authenticatedHttpClient = new AuthenticatedHttpClient($httpClient, $authenticationApi, $authentication);

        $resourceClient = new ResourceClient(
            $authenticatedHttpClient,
            $uriGenerator
        );

        $client = new LeadClient(
            $authentication,
            new LeadApi($resourceClient),
            new OfferApi($resourceClient)
        );

        return $client;
    }

    /**
     * @return Client
     */
    protected function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
    }

    /**
     * @return RequestFactory
     */
    protected function getRequestFactory()
    {
        if (null === $this->requestFactory) {
            $this->requestFactory = MessageFactoryDiscovery::find();
        }

        return $this->requestFactory;
    }
}
