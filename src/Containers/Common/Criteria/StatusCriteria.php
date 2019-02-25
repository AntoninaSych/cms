<?php

namespace Edhub\CMS\Containers\Common\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Edhub\Shared\Criteria\Criteria;

class StatusCriteria implements CriteriaInterface, Criteria
{
    const NAME = 'status';
    /**
     * @var array
     */
    private $statuses;

    public function __construct(array $statuses = [])
    {
        $this->statuses = array_map('intval', $statuses);
    }
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->whereIn('status', $this->statuses);
    }

}