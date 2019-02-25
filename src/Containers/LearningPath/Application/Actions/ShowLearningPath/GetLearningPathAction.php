<?php
namespace Edhub\CMS\Containers\LearningPath\Application\Actions\ShowLearningPath;


use Edhub\Shared\Criteria\CriteriaFactory;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\LearningPathRepository;
use Edhub\CMS\Containers\LearningPath\Domain\Values\LearningPathId;

class GetLearningPathAction
{

    private $learningPath;
    /**
     * @var CriteriaFactory
     */
    private $criteria;

    public function __construct(LearningPathRepository $learningPathRepository, CriteriaFactory $criteriaFactory)
    {
        $this->learningPath = $learningPathRepository;
        $this->criteria = $criteriaFactory;
    }

    public function run(GetLearningPathTransporter $transporter):LearningPath
    {
        $learningPathId = LearningPathId::new((int)$transporter->id);
        $filters = $transporter->filters ?? [];

        foreach ($filters as $filter) {
            if (!empty($filter['name'])) {
                $criteria = $this->criteria->create($filter['name'], (array)$filter['value']);
                $this->learningPath->pushCriteria($criteria);
            }
        }

        return $this->learningPath->getOne($learningPathId);
    }
}