<?php

namespace Edhub\CMS\Containers\Common\Criteria;

use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Edhub\Shared\Criteria\Criteria;

class IdsCriteria implements CriteriaInterface, Criteria
{
    const NAME = 'ids';

    /**
     * @var array
     */
    private $ids;

    public function __construct(array $ids = [])
    {
        $this->ids = array_map('intval', $ids);
    }

    /** @param LearningPath $model */
    public function apply($model, RepositoryInterface $repository)
    {
        if (!empty($this->ids)) {
            $model = $model->whereIn('id', $this->ids);
        }

        return $model;
    }

}