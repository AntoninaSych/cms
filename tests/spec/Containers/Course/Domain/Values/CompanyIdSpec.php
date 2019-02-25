<?php

namespace spec\Edhub\CMS\Containers\Courses\Domain\Values;

use Edhub\CMS\Containers\Courses\Domain\Values\CompanyId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompanyIdSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CompanyId::class);
    }

    public function it_should_keep_company_identifier()
    {
        $companyId = 1;

        $this->beConstructedThrough('new', [$companyId]);

        $this->value()->shouldBe($companyId);
    }
}
