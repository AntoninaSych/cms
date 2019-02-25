<?php

namespace Edhub\CMS\Containers\Common\Criteria;


use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Edhub\Shared\Criteria\Criteria;


class CompanyCriteria implements CriteriaInterface, Criteria
{
    /**
     * @var array
     */
    private $companyIds;

    public function __construct(array $companyIds = [])
    {
        $this->companyIds = array_map('intval', $companyIds);
    }

    /** @param Course|LearningPath $model */
    public function apply($model, RepositoryInterface $repository)
    {
        if (!empty($this->companyIds)) {
            $model = $model->whereHas('companies_relation', function ($query) {
                $query->whereIn('company_id', $this->companyIds);
            });
        }

        return $model;
    }

    public function getValues(): array
    {
        return $this->companyIds;
    }
}