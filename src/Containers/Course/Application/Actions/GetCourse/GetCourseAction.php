<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\GetCourse;

use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;
use Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository;

class GetCourseAction
{
    /**
     * @var CourseRepository
     */
    private $courses;

    public function __construct(CourseRepository $courses)
    {
        $this->courses = $courses;
    }

    public function run(GetCourseTransporter $input): Course
    {
        $course = $this->courses->getOne(CourseId::new($input->id));

        return $course;
    }
}