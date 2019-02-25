<?php

namespace spec\Edhub\CMS\Containers\Courses\Domain\Collections;

use Edhub\Shared\Exceptions\InvalidArgumentException;
use Edhub\CMS\Containers\Courses\Domain\Collections\CourseCompanies;
use Edhub\CMS\Containers\Courses\Domain\Entities\CourseCompany;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CourseCompaniesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CourseCompanies::class);
    }

    public function it_should_throw_exception_if_items_not_of_CourseCompany_type()
    {
        $this->beConstructedWith([1]);
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_should_keep_items_of_CourseCompany_type(CourseCompany $company1, CourseCompany $company2)
    {
        $this->beConstructedWith([$company1, $company2]);
        $this->shouldHaveCount(2);
        $this->toArray()->shouldContain($company1);
        $this->toArray()->shouldContain($company2);
    }
}
