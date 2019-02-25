<?php

namespace spec\Edhub\CMS\Containers\Courses\Application\Actions\GetCourseList;

use Edhub\Shared\Collections\PaginatedCollection;
use Edhub\CMS\Containers\Courses\Application\Actions\GetCourseList\GetCourseListAction;
use Edhub\CMS\Containers\Courses\Application\Actions\GetCourseList\GetCourseListTransporter;
use Edhub\CMS\Containers\Courses\Domain\Repositories\CourseRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GetCourseListActionSpec extends ObjectBehavior
{
    public function let(CourseRepository $coursesRepository)
    {
        $this->beConstructedWith($coursesRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetCourseListAction::class);
    }

    public function it_should_return_list_of_courses(CourseRepository $coursesRepository, PaginatedCollection $paginatedCollection, GetCourseListTransporter $getCourseListTransporter)
    {
        $coursesRepository->paginate()->willReturn($paginatedCollection);

        $this->run($getCourseListTransporter)->shouldReturn($paginatedCollection);
    }
}
