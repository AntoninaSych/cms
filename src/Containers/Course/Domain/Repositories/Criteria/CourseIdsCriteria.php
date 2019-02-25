<?php

namespace Edhub\CMS\Containers\Course\Domain\Repositories\Criteria;

use Edhub\Shared\Criteria\Criteria;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class CourseIdsCriteria implements CriteriaInterface, Criteria
{
    const NAME = 'courses.ids';

    /**
     * @var array
     */
    private $ids;

    public function __construct(array $ids = [])
    {
        $this->ids = array_map('intval', $ids);
    }

    /** @param Course $model */
    public function apply($model, RepositoryInterface $repository)
    {
        if ($model->getModel() instanceof Course === false) {
            return $model;
        }

        if (!empty($this->ids)) {
            $model = $model->whereIn('id', $this->ids);
        }

        return $model;
    }

}