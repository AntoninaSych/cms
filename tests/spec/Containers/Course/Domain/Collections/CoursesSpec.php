<?php

namespace spec\Edhub\CMS\Containers\Courses\Domain\Collections;

use Edhub\Shared\Exceptions\InvalidArgumentException;
use Edhub\CMS\Containers\Courses\Domain\Collections\Courses;
use Edhub\CMS\Containers\Courses\Domain\Entities\Course;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CoursesSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Courses::class);
    }

    public function it_should_throw_exception_if_items_not_of_CourseCompany_type()
    {
        $this->beConstructedWith([1]);
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_should_keep_items_of_CourseCompany_type(Course $course1, Course $course2)
    {
        $this->beConstructedWith([$course1, $course2]);
        $this->shouldHaveCount(2);
        $this->toArray()->shouldContain($course1);
        $this->toArray()->shouldContain($course1);
    }
}
