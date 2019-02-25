<?php

namespace spec\Edhub\CMS\Containers\Courses\Domain\Values;

use Edhub\CMS\Containers\Courses\Domain\Values\CourseId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CourseIdSpec extends ObjectBehavior
{
    public function it_should_keep_course_identifier()
    {
        $this->beConstructedThrough('new', [1]);

        $this->value()->shouldBe(1);
    }
}
