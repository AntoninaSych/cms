<?php

namespace Edhub\CMS\Containers\Course\Domain\Repositories\Criteria;

use Edhub\Shared\Criteria\Criteria;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class LearningPathCriteria implements CriteriaInterface, Criteria
{
    /**
     * @var array
     */
    private $pathsId;

    public function __construct(array $pathsId = [])
    {
        $this->pathsId = array_map('intval', $pathsId);
    }

    /** @param Course $model */
    public function apply($model, RepositoryInterface $repository)
    {
        if ($model->getModel() instanceof Course === false) {
            return $model;
        }

        if (!empty($this->pathsId)) {
            $model = $model
                ->join('learning_path_courses as lps', 'lps.course_id', '=', 'courses.id')
                ->whereIn('lps.learning_path_id', $this->pathsId);
        }

        return $model;
    }

}