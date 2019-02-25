<?php


namespace Edhub\CMS\Containers\LearningPath\Domain\Repositories\Criteria;

use Edhub\Shared\Criteria\SearchCriteria as BaseSearchCriteria;

class SearchCriteria extends BaseSearchCriteria
{
    protected function getSearchFields(): array
    {
        return ['title'];
    }
}