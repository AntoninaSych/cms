<?php

namespace Edhub\CMS\Containers\CourseCategory\Application\Actions\GetCourseCategory;


use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryRepository;

class GetCourseCategoryAction
{
    /**
     * @var CourseCategoryRepository
     */
    private $categories;

    public function __construct(CourseCategoryRepository $categories)
    {
        $this->categories = $categories;
    }

    public function run(GetCourseCategoryTransporter $input)
    {
        $courseCategoryId = $input->id;
        $courseCategory = $this->categories->getOne($courseCategoryId);

        return $courseCategory;
    }
}