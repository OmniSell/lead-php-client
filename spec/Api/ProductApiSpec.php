<?php

namespace spec\Omni\Lead\Api;

use Omni\Lead\Api\ListableResourceInterface;
use Omni\Lead\Api\LeadApi;
use Omni\Lead\Api\LeadApiInterface;
use Omni\Lead\Client\ResourceClientInterface;
use Omni\Lead\Exception\InvalidArgumentException;
use Omni\Lead\Pagination\PageInterface;
use Omni\Lead\Pagination\PageFactoryInterface;
use Omni\Lead\Pagination\ResourceCursorFactoryInterface;
use Omni\Lead\Pagination\ResourceCursorInterface;
use Omni\Lead\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;

class LeadApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient
    ) {
        $this->beConstructedWith($resourceClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LeadApi::class);
        $this->shouldImplement(LeadApiInterface::class);
    }

    function it_creates_a_lead($resourceClient)
    {
        $resourceClient
            ->createResource(
                LeadApi::LEADS_URI,
                [],
                ['identifier' => 'foo', 'family' => 'bar']
            )
            ->willReturn(201);

        $this->create('foo', ['family' => 'bar'])->shouldReturn(201);
    }

    function it_upserts_a_lead($resourceClient)
    {
        $resourceClient
            ->upsertResource(LeadApi::LEAD_URI, ['foo'], ['identifier' => 'foo' , 'family' => 'bar'])
            ->willReturn(204);

        $this->upsert('foo', ['identifier' => 'foo' , 'family' => 'bar'])
            ->shouldReturn(204);
    }
}
