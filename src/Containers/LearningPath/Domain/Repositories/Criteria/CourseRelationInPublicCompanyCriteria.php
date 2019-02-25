<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Repositories\Criteria;


use Edhub\Shared\Criteria\Criteria;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class CourseRelationInPublicCompanyCriteria implements CriteriaInterface, Criteria
{
    const NAME = 'leanpaths.courses.companies.public';

    /**
     * @var array
     */
    private $companiesIds;

    public function __construct(array $companies = [])
    {
        $this->companiesIds = array_map('intval', $companies);
    }

    /** @var LearningPath $model */
    public function apply($model, RepositoryInterface $repository)
    {
        if ($model->getModel() instanceof LearningPath === false) {
            return $model;
        }

        return $model->with([
            'courses_relation' => function ($query) {
                $query->whereHas('companies_relation', function ($query) {
                    $query->whereIn('company_id', $this->companiesIds)->where('is_public', '=', 1);
                });
            }
        ]);
    }

}