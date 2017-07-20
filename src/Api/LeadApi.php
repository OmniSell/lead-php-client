<?php

namespace Omni\Lead\Api;

use Omni\Lead\Client\ResourceClientInterface;

/**
 * API implementation to manage the leads.
 */
class LeadApi implements LeadApiInterface
{
    const LEADS_URI = 'api/leads/';
    const LEAD_URI = 'api/leads/%s';

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
    public function create(array $data = [])
    {
        return $this->resourceClient->createResource(static::LEADS_URI, [], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert($code, array $data = [])
    {
        return $this->resourceClient->upsertResource(static::LEAD_URI, [$code], $data);
    }
}
