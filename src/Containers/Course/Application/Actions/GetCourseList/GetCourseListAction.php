<?php

namespace Edhub\CMS\Containers\Course\Application\Actions\GetCourseList;

use Edhub\Shared\Collections\PaginatedCollection;
use Edhub\Shared\Criteria\CriteriaFactory;
use Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository;
use Edhub\Shared\Values\PaginationPresenter;

class GetCourseListAction
{
    /**
     * @var CourseRepository
     */
    private $courses;
    /**
     * @var CriteriaFactory
     */
    private $criteria;

    public function __construct(CourseRepository $courses, CriteriaFactory $criteria)
    {
        $this->courses = $courses;
        $this->criteria = $criteria;
    }

    public function run(GetCourseListTransporter $transporter): PaginatedCollection
    {
        $filters = $transporter->filters ?? [];
        $sorting = $transporter->sorting ?? [];
        $perPage = $transporter->perPage ?? CourseRepository::DEFAULT_LIMIT;

        foreach ($filters as $filter) {
            if (!empty($filter['name'])) {
                $criteria = $this->criteria->create($filter['name'], (array)$filter['value']);
                $this->courses->pushCriteria($criteria);
            }
        }

        foreach ($sorting as $sortingCriteria) {
            $sortingCriteria = $this->criteria->create('sorting', $sortingCriteria);
            $this->courses->pushCriteria($sortingCriteria);
        }

        /** @var \Illuminate\Pagination\LengthAwarePaginator $courses */
        $courses = $this->courses->paginate($perPage);

        return new PaginatedCollection(
            iterator_to_array($courses),
            $courses->total(),
            $perPage
        );
    }
}
