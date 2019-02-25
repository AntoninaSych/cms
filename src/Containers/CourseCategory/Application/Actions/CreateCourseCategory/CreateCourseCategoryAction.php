<?php

namespace Edhub\CMS\Containers\CourseCategory\Application\Actions\CreateCourseCategory;

use Edhub\CMS\Containers\Common\Values\Language;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategory;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryRepository;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryTitle;

class CreateCourseCategoryAction
{
    /**
     * @var CourseCategoryRepository
     */
    private $categories;

    public function __construct(CourseCategoryRepository $categories)
    {
        $this->categories = $categories;
    }

    public function run(CreateCourseCategoryTransporter $input): CourseCategory
    {
        $inputTitles = $input->titles;

        $category = new CourseCategory();
        $category->changeTitle(...$inputTitles);

        $this->categories->save($category);

        return $category;
    }

}