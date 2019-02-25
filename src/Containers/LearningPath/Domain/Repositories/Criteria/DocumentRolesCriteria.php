<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Repositories\Criteria;

use Edhub\Shared\Criteria\Criteria;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class DocumentRolesCriteria implements CriteriaInterface, Criteria
{
    const NAME = 'document.roles';
    private $roles = [];

    public function __construct(array $roles = [])
    {
        $this->roles = array_map('intval', $roles);
    }

    /** @param LearningPath $model */
    public function apply($model, RepositoryInterface $repository)
    {
        if ($model->getModel() instanceof LearningPath === false) {
            return $model;
        }

        if (!empty($this->roles)) {
            $model = $model->with(['documents_relation' => function ($query) {
                $query->whereHas('document_role_relation', function ($query) {
                    $query->whereIn('role_id', $this->roles);
                });
            }]);
        }

        return $model;
    }
}