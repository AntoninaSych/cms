<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 21-Nov-18
 * Time: 12:15
 */
namespace Edhub\CMS\Containers\LearningPath\Domain\Repositories;

use Edhub\Shared\Criteria\Criteria;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\CMS\Containers\LearningPath\Domain\Values\LearningPathId;


interface LearningPathRepository
{
    const DEFAULT_LIMIT = 20;

    public function save(LearningPath $LearningPath);

    public function getOne(LearningPathId $id): LearningPath;

    /** @return LearningPath[] */
    public function findByIds(LearningPathId ...$ids): iterable;

    public function pushCriteria($criteria);
}

