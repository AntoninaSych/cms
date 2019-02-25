<?php

namespace Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList;


use Edhub\CMS\Containers\LearningPath\Domain\Repositories\LearningPathRepository;
use Edhub\Shared\Collections\PaginatedCollection;
use Edhub\Shared\Criteria\CriteriaFactory;

class GetLearningPathListAction
{

    /**
     * @var LearningPathRepository
     */
    private $learningPath;
    /**
     * @var CriteriaFactory
     */
    private $criteria;

    public function __construct(LearningPathRepository $learningPath, CriteriaFactory $criteria)
    {
        $this->learningPath = $learningPath;
        $this->criteria = $criteria;
    }

    public function run(GetLearningPathListTransporter $transporter): PaginatedCollection
    {
        $filters = $transporter->filters ?? [];
        $search = $transporter->search ?? '';
        $sorting = $transporter->sorting ?? [];
        $perPage = $transporter->perPage ?: LearningPathRepository::DEFAULT_LIMIT;

        foreach ($filters as $filter) {
            if (!empty($filter['name'])) {
                $criteria = $this->criteria->create($filter['name'], (array)$filter['value']);
                $this->learningPath->pushCriteria($criteria);
            }
        }

        if (!empty($search)) {
            $searchCriteria = $this->criteria->create('search', [$search]);
            $this->learningPath->pushCriteria($searchCriteria);
        }

        foreach ($sorting as $sortingCriteria) {
            $sortingCriteria = $this->criteria->create('sorting', $sortingCriteria);
            $this->learningPath->pushCriteria($sortingCriteria);
        }

        $learningPaths = $this->learningPath->paginate($perPage);

        return new PaginatedCollection(
            iterator_to_array($learningPaths),
            $learningPaths->total(),
            $perPage
        );

    }
}