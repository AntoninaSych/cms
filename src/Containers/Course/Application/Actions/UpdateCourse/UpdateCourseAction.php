<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\UpdateCourse;

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

class UpdateCourseAction
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
    public  $task;
    public function __construct(CourseRepository $courses, CourseCategoryRepository $categories,
                                GetCurrentUserCompaniesTask $task)
    {
        $this->courses = $courses;
        $this->categories = $categories;
        $this->task = $task;
    }
    public function run(UpdateCourseTransporter $input): Course
    {
        [$id, $title, $subtitle, $description, $status, $language, $companies, $categoriesIds, $image] = [
            (int)$input->id, (string)$input->title, (string)$input->subtitle, $input->description, (int)$input->status, (string)$input->language,
            (array)$input->companies, (array)$input->categories, $input->image
        ];

        $course = $this->courses->getOne(CourseId::new($id));
        $course->changeTitle($title);
        $course->changeSubtitle($subtitle);
        $course->changeDescription($description);
        $course->changeStatus(CourseStatus::new($status));
        $course->changeLanguage(Language::new($language));
        if (!is_null($image)) {
            $course->replaceImage($image);
        }

        $this->courses->save($course);

        $categories = $this->categories->findByIds(...$categoriesIds);
        $course->changeCategories($categories);

        $courseCompaniesList = $this->prepareCompaniesInfo($course->id(), $companies);

        $course->assignCompanies($courseCompaniesList, $this->task);

        $this->rebuildChapters($course, $input);

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

    private function rebuildChapters(Course $course, UpdateCourseTransporter $input): void
    {
        $rawChapters = (array)$input->chapters;
        //@TODO Use ordered chapters
        $positionedChapters = $this->setupChaptersPosition($rawChapters);
        $course->rebuildChapters($positionedChapters);
    }

    /**
     * Setup positions for each chapter (nested and top ones).
     *
     * @param array $chapters
     * @return array
     */
    private function setupChaptersPosition(array $chapters): array
    {
        $position = 1;
        foreach ($chapters as $key => $chapter) {
            $chapter['position'] = $position;
            $chapter['children'] = $this->setupChaptersPosition($chapter['children']);
            $chapters[$key] = $chapter;
            $position++;
        }

        return $chapters;
    }
}