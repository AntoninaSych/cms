<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\CreateCourse;

use Edhub\CMS\Containers\Common\Tasks\GetCurrentUserCompaniesTask;
use Edhub\CMS\Containers\Course\Domain\Collections\CourseCompanies;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Edhub\CMS\Containers\Course\Domain\Entities\CourseCompany;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryRepository;
use Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository;
use Edhub\CMS\Containers\Course\Domain\Values\CompanyId;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;
use Edhub\CMS\Containers\Course\Domain\Values\CourseStatus;
use Edhub\CMS\Containers\Common\Values\Language;

class CreateCourseAction
{
    /**
     * @var CourseRepository
     */
    private $courses;
    /**
     * @var CourseCategoryRepository
     */
    private $categories;

    /**
     * @var GetCurrentUserCompaniesTask
     */
    private $task;

    public function __construct(
        CourseRepository $courses, CourseCategoryRepository $categories, GetCurrentUserCompaniesTask $task)
    {



        $this->courses = $courses;

        $this->categories = $categories;
        $this->task = $task;
    }

    public function run(CreateCourseTransporter $input)
    {
        [$title, $subtitle, $description, $status, $language, $companies, $categoriesIds, $image] = [
            $input->title, $input->subtitle, $input->description, $input->status, $input->language,
            $input->companies, $input->categories, $input->image
        ];

        $course = Course::create($title, Language::new($language));
        $course->changeSubtitle($subtitle);
        $course->changeDescription($description);
        $course->changeStatus(CourseStatus::new($status));

        $this->courses->save($course);

        if (!is_null($image)) {
            $course->replaceImage($image);
        }

        $categories = $this->categories->findByIds(...$categoriesIds);
        $course->changeCategories($categories);

        $courseCompaniesList = $this->prepareCompaniesInfo($course->id(), $companies);
        $course->assignCompanies($courseCompaniesList, $this->task);

        return $course;
    }

    private function prepareCompaniesInfo(CourseId $courseId, array $companies): CourseCompanies
    {
        $courseCompaniesList = new CourseCompanies();
        foreach ($companies as $companyInfo) {
            $courseCompaniesList->add(
                CourseCompany::create(
                    $courseId,
                    CompanyId::new((int)$companyInfo['company']),
                    (bool)$companyInfo['isPublic'])
            );
        }

        return $courseCompaniesList;
    }
}
