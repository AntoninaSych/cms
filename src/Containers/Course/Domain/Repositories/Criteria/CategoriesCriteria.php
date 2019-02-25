<?php

namespace Edhub\CMS\Containers\Course\Domain\Repositories\Criteria;

use Edhub\Shared\Criteria\Criteria;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class CategoriesCriteria implements CriteriaInterface, Criteria
{
    /**
     * @var array
     */
    private $categories;

    public function __construct(array $categories = [])
    {
        $this->categories = array_map('intval', $categories);
    }

    public function apply($model, RepositoryInterface $repository)
    {
        if ($model->getModel() instanceof Course === false) {
            return $model;
        }

        if (!empty($this->categories)) {
            $model = $model->whereHas('categories_relation', function ($query) {
                $query->whereIn('course_category_id', $this->categories);
            });
        }

        return $model;
    }

}