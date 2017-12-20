<?php

namespace Omni\Lead\Api;

use Omni\Lead\Client\ResourceClientInterface;

/**
 * API implementation to manage the leads.
 */
class OfferApi implements OfferApiInterface
{
    const OFFER_URI = 'api/offers/%s';
    const OFFER_COMPLETE_URI = 'api/offers/%s/complete';

    /** @var ResourceClientInterface */
    protected $resourceClient;

    /**
     * @param ResourceClientInterface $resourceClient
     */
    public function __construct(
        ResourceClientInterface $resourceClient
    ) {
        $this->resourceClient = $resourceClient;
    }

    /**
     * {@inheritdoc}
     */
    public function get($code)
    {
        return $this->resourceClient->getResource(static::OFFER_URI, [$code]);
    }

    /**
     * {@inheritdoc}
     */
    public function complete($code)
    {
        return $this->resourceClient->createResource(static::OFFER_COMPLETE_URI, [$code], []);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert($code, array $data = [])
    {
        return $this->resourceClient->upsertResource(static::OFFER_URI, [$code], $data);
    }
}
