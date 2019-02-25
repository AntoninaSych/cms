<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\DeleteCourse;


use Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;

class DeleteCourseAction
{
    /**
     * @var CourseRepository
     */
    private $courses;

    public function __construct(CourseRepository $courses)
    {
        $this->courses = $courses;
    }

    public function run(DeleteCourseTransporter $transporter)
    {
        $courseId = CourseId::new($transporter->id);
        $course = $this->courses->getOne($courseId);
        $this->courses->delete($course);
    }
}