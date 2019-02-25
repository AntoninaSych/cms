<?php

namespace Edhub\CMS\Containers\Course\Domain\Repositories\Criteria;

use Edhub\Shared\Criteria\Criteria;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class PublicCompanyCriteria implements CriteriaInterface, Criteria
{
    const NAME = 'companies.public';
    /**
     * @var array
     */
    private $companyIds;

    public function __construct(array $companyIds = [])
    {
        $this->companyIds = array_map('intval', $companyIds);
    }

    /** @param Course $model */
    public function apply($model, RepositoryInterface $repository)
    {
        if ($model->getModel() instanceof Course === false) {
            return $model;
        }

        if (!empty($this->companyIds)) {
            $model = $model->whereHas('companies_relation', function (Builder $query) {
                $query
                    ->whereIn('company_id', $this->companyIds)
                    ->where('is_public', '=', 1);
            });
        }

        return $model;
    }
}