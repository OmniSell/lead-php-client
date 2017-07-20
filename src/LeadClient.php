<?php

namespace Omni\Lead;

use Omni\Lead\Api\LeadApiInterface;
use Omni\Lead\Api\OfferApiInterface;
use Omni\Lead\Security\Authentication;

/**
 * This class is the implementation of the client to use the Omni.Sell Lead API.
 */
class LeadClient implements LeadClientInterface
{
    /** @var Authentication */
    protected $authentication;

    /** @var LeadApiInterface */
    protected $leadApi;

    /** @var OfferApiInterface */
    protected $offerApi;

    /**
     * @param Authentication    $authentication
     * @param LeadApiInterface  $leadApi
     * @param OfferApiInterface $offerApi
     */
    public function __construct(
        Authentication $authentication,
        LeadApiInterface $leadApi,
        OfferApiInterface $offerApi
    ) {
        $this->authentication = $authentication;
        $this->leadApi = $leadApi;
        $this->offerApi = $offerApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return $this->authentication->getAccessToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken()
    {
        return $this->authentication->getRefreshToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getLeadApi()
    {
        return $this->leadApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getOfferApi()
    {
        return $this->offerApi;
    }
}
