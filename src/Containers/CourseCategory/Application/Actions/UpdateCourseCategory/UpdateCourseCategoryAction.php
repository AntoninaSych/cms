<?php

namespace Edhub\CMS\Containers\CourseCategory\Application\Actions\UpdateCourseCategory;


use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategory;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryRepository;

class UpdateCourseCategoryAction
{
    /**
     * @var CourseCategoryRepository
     */
    private $categories;

    public function __construct(CourseCategoryRepository $categories)
    {
        $this->categories = $categories;
    }

    public function run(UpdateCourseCategoryTransporter $input): CourseCategory
    {
        $id = $input->id;
        $inputTitles = $input->titles;

        $category = $this->categories->getOne($id);
        $category->changeTitle(...$inputTitles);

        $this->categories->save($category);

        return $category;
    }
}