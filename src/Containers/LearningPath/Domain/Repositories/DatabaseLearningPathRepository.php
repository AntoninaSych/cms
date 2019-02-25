<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Repositories;

use Edhub\CMS\Containers\Common\Criteria\CompanyCriteria;
use Edhub\Shared\Criteria\Criteria;
use Edhub\Shared\Exceptions\InvalidArgumentException;
use Edhub\CMS\Containers\Common\Tasks\GetCurrentUserCompaniesTask;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\CMS\Containers\LearningPath\Domain\Exceptions\LearningPathNotFound;
use Edhub\CMS\Containers\LearningPath\Domain\Values\LearningPathId;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Repository\Eloquent\BaseRepository;
use Psr\Log\LoggerInterface;

class DatabaseLearningPathRepository extends BaseRepository implements LearningPathRepository
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $availableUserCompanies;

    public function __construct(Application $app, LoggerInterface $logger, GetCurrentUserCompaniesTask $task)
    {
        parent::__construct($app);
        $this->logger = $logger;
        $this->availableUserCompanies = $task->run();
    }

    public function model()
    {
        return LearningPath::class;
    }

    public function save(LearningPath $learningPath): void
    {
        $learningPath->saveOrFail();
    }

    public function getOne(LearningPathId $id): LearningPath
    {
        $this->criteria->push(new CompanyCriteria($this->availableUserCompanies));

        try {
            $learningPath = $this->find($id->value());
        } catch (ModelNotFoundException $exception) {
            throw new LearningPathNotFound();
        }

        if (empty($learningPath)) {
            throw new LearningPathNotFound();
        }

        return $learningPath;
    }

    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        array_filter($this->criteria->toArray(), function ($criteriaItem) {
            return $criteriaItem instanceof CompanyCriteria;
        });
        if (!empty($criteriaItem)) {
            $companyCriteria = array_shift($companyCriteria);

            $companyCriteriaCompanyIds = $companyCriteria->getValues();


            $criteriaIn = array_intersect($companyCriteriaCompanyIds, $this->availableUserCompanies);
            $this->criteria->push(new CompanyCriteria($criteriaIn));

        } else {
            $this->criteria->push(new CompanyCriteria($this->availableUserCompanies));
        }

        $results = parent::paginate($limit, $columns, $method);

        return $results;
    }


    public function findByIds(LearningPathId ...$ids): iterable
    {
        $this->criteria->push(new CompanyCriteria($this->availableUserCompanies));

        $rawIds = array_map(function (LearningPathId $learningPathId) {
            return $learningPathId->value();
        }, $ids);
        $learningPathList = $this->findWhereIn('id', $rawIds);

        return $learningPathList;
    }

    public function pushCriteria($criteria): void
    {
        if ($criteria instanceof Criteria === false) {
            throw new InvalidArgumentException('Criteria must implement Criteria interface.');
        }
        parent::pushCriteria($criteria);
    }
}