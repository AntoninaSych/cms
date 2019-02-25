<?php

namespace Edhub\CMS\Containers\Course\Domain\Repositories;

use Edhub\CMS\Containers\Chapter\Domain\Entities\Chapter;
use Edhub\CMS\Containers\Common\Criteria\CompanyCriteria;
use Edhub\CMS\Containers\Common\Tasks\GetCurrentUserCompaniesTask;
use Edhub\CMS\Containers\Course\Domain\Collections\Courses;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Edhub\CMS\Containers\Course\Domain\Exceptions\CourseNotFound;
use Edhub\CMS\Containers\Course\Domain\Exceptions\CourseNotSaved;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;
use Psr\Log\LoggerInterface;

class CourseRepository extends BaseRepository
{
    const DEFAULT_LIMIT = 20;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $availableUserCompanies;

    public function __construct(Application $app, LoggerInterface $logger, GetCurrentUserCompaniesTask $task)
    {
        parent::__construct($app);
        $this->logger = $logger;
        $this->availableUserCompanies = $task->run();
    }

    public function model()
    {
        return Course::class;
    }

    public function search(CourseId $courseId, string $query = ''): array
    {
        // Check access to a course
        $course = $this->getOne($courseId);

        return Chapter::search($query)->where('course_id', $course->id()->value())->raw();
    }

    public function findByIds(CourseId ...$courseIds): Courses
    {
        $this->criteria->push(new CompanyCriteria($this->availableUserCompanies));
        $courseRawIds = array_map(function (CourseId $courseId) {
            return $courseId->value();
        }, $courseIds);
        $courses = $this->findWhereIn('id', $courseRawIds)->all();

        return Courses::make($courses);
    }

    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        array_filter($this->criteria->toArray(), function ($criteriaItem) {
           return $criteriaItem instanceof CompanyCriteria;
        });
        if (!empty($criteriaItem)) {
            $companyCriteria = array_shift($companyCriteria);

            $companyCriteriaCompanyIds = $companyCriteria->getValues();


            $criteriaIn = array_intersect($companyCriteriaCompanyIds, $this->availableUserCompanies);
            $this->criteria->push(new CompanyCriteria($criteriaIn));

        } else {
            $this->criteria->push(new CompanyCriteria($this->availableUserCompanies));
        }
        $results = parent::paginate($limit, $columns, $method);

        return $results;
    }

    public function getOne(CourseId $courseId): Course
    {
        $this->criteria->push(new CompanyCriteria($this->availableUserCompanies));
        $course = $this->findWhere(['id' => $courseId->value()])->first();
        if (empty($course)) {
            throw new CourseNotFound();
        }

        return $course;
    }

    public function save(Course $course): void
    {
        try {
            if (!$course->push()) {
                throw new  CourseNotSaved();
            }
        } catch (\Throwable $exception) {
            $this->logger->warning($exception->getMessage());
            throw new  CourseNotSaved();
        }
    }

    public function delete($course)
    {
        if ($course instanceof Course !== false) {
            $course->delete();
        }
    }

}