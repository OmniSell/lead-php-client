<?php

namespace spec\Omni\Lead;

use Omni\Lead\Api\OfferApiInterface;
use Omni\Lead\LeadClient;
use Omni\Lead\LeadClientInterface;
use Omni\Lead\Api\LeadApiInterface;
use Omni\Lead\Security\Authentication;
use PhpSpec\ObjectBehavior;

class LeadClientSpec extends ObjectBehavior
{
    function let(
        Authentication $authentication,
        LeadApiInterface $leadApi,
        OfferApiInterface $offerApi
    )
    {
        $this->beConstructedWith($authentication, $leadApi, $offerApi);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(LeadClientInterface::class);
        $this->shouldHaveType(LeadClient::class);
    }

    function it_gets_access_token($authentication)
    {
        $authentication->getAccessToken()->willReturn('foo');

        $this->getToken()->shouldReturn('foo');
    }

    function it_gets_refresh_token($authentication)
    {
        $authentication->getRefreshToken()->willReturn('bar');

        $this->getRefreshToken()->shouldReturn('bar');
    }

    function it_gets_lead_api($leadApi)
    {
        $this->getLeadApi()->shouldReturn($leadApi);
    }

    function it_gets_offer_api($offerApi)
    {
        $this->getOfferApi()->shouldReturn($offerApi);
    }
}
