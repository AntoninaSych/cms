<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\SearchCourseContent;

use Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;

class SearchCourseContentAction
{
    /**
     * @var CourseRepository
     */
    private $courses;

    public function __construct(CourseRepository $courses)
    {
        $this->courses = $courses;
    }

    public function run(SearchCourseContentTransporter $input): array
    {
        $courseId = CourseId::new($input->course);
        $query = (string)$input->query;
        $searchResult = $this->courses->search($courseId, $query);

        return $searchResult;
    }
}