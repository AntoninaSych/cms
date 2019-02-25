<?php

namespace Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList;

use Edhub\CMS\Containers\Common\Tasks\GetCurrentUserCompaniesTask;
use Edhub\CMS\Containers\Common\Values\Language;
use Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository;
use Edhub\CMS\Containers\Course\Domain\Values\CompanyId;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\LearningPathRepository;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Code;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Description;
use Edhub\CMS\Containers\LearningPath\Domain\Values\LearningPathId;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Meta;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Points;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Status;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Subtitle;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Title;

class UpdateLearningPathAction
{
    private $learningPath;
    private $currentUserCompaniesTask;
    /**
     * @var CourseRepository
     */
    private $courses;

    public function __construct(LearningPathRepository $learningPath, CourseRepository $courses, GetCurrentUserCompaniesTask $task)
    {
        $this->learningPath = $learningPath;
        $this->courses = $courses;
        $this->currentUserCompaniesTask = $task;
    }

    public function run(UpdateLearningPathTransporter $transporter)
    {
        $coursesIdsList = $transporter->courses;
        $companiesIdsList = $transporter->companies;

        [$title, $subtitle, $description, $status, $points, $code, $language, $image, $meta, $id] = [
            $transporter->title, $transporter->subtitle, $transporter->description, $transporter->status, $transporter->points, $transporter->code,
            $transporter->language, $transporter->image, $transporter->meta, $transporter->id
        ];

        $id = LearningPathId::new((int)$id);
        $learningPath = $this->learningPath->getOne($id);
        $learningPath->changeTitle(Title::new((string)$title));
        $learningPath->changeSubtitle(Subtitle::new((string)$subtitle));
        $learningPath->changeDescription(Description::new($description));
        $learningPath->changeStatus(Status::new((int)$status));
        $learningPath->changePoints(Points::new((int)$points));
        $learningPath->changeCode(Code::new((string)$code));
        $learningPath->changeMeta(Meta::new((array)$meta));
        $learningPath->changeLanguage(Language::new((string)$language));
        $this->learningPath->save($learningPath);

        if (!is_null($image)) {
            $learningPath->replaceImage($image);
        }

        if (!is_null($coursesIdsList)) {
            $this->updateCourses($learningPath, $coursesIdsList);
        }

        if (!is_null($companiesIdsList)) {
            $this->updateCompanies($learningPath, $companiesIdsList);
        }

        return $learningPath;
    }

    private function updateCourses(LearningPath $learningPath, array $coursesRawIdsList): void
    {
        $coursesIdsList = array_map(function (int $courseRawId) {
            return CourseId::new($courseRawId);
        }, $coursesRawIdsList);
        $courses = $this->courses->findByIds(...$coursesIdsList);
        $learningPath->syncCourses($courses, $this->currentUserCompaniesTask);
    }

    private function updateCompanies(LearningPath $learningPath, array $coursesIdsList): void
    {
        $companiesList = array_map(function (int $companyId) {
            return CompanyId::new($companyId);
        }, $coursesIdsList);
        $learningPath->syncCompanies($this->currentUserCompaniesTask,...$companiesList);
    }

}