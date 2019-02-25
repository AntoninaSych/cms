<?php

namespace spec\Edhub\CMS\Containers\Courses\Domain\Values;

use Edhub\Shared\Exceptions\DomainException;
use Edhub\CMS\Containers\Courses\Domain\Values\CourseStatus;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CourseStatusSpec extends ObjectBehavior
{
    public function it_should_create_status_from_input_value()
    {
        $this->beConstructedThrough('new', [1]);
        $this->value()->shouldBe(1);
    }

    public function it_should_create_draft_status()
    {
        $this->beConstructedThrough('draft');
        $this->value()->shouldBe(1);
    }

    public function it_should_create_published_status()
    {
        $this->beConstructedThrough('published');
        $this->value()->shouldBe(2);
    }

    public function it_should_create_archived_status()
    {
        $this->beConstructedThrough('archived');
        $this->value()->shouldBe(3);
    }

    public function it_should_throw_domain_exception_if_status_is_not_implemented()
    {
        $this->beConstructedThrough('new', [999]);
        $this->shouldThrow(DomainException::class)->duringInstantiation();
    }
}
