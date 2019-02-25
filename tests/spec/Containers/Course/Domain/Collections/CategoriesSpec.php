<?php

namespace spec\Edhub\CMS\Containers\Courses\Domain\Collections;

use Edhub\Shared\Exceptions\InvalidArgumentException;
use Edhub\CMS\Containers\Courses\Domain\Collections\Categories;
use Edhub\CMS\Containers\Courses\Domain\Entities\CourseCategory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CategoriesSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Categories::class);
    }

    public function it_should_throw_exception_if_items_not_of_CourseCompany_type()
    {
        $this->beConstructedWith([1]);
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_should_keep_items_of_CourseCompany_type(CourseCategory $category1, CourseCategory $category2)
    {
        $this->beConstructedWith([$category1, $category2]);
        $this->shouldHaveCount(2);
        $this->toArray()->shouldContain($category1);
        $this->toArray()->shouldContain($category2);
    }
}
