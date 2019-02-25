<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Repositories;

use Edhub\CMS\Containers\LearningPath\Domain\Collection\NovisLearningPaths;

class ApiNovisLearningPathRepository implements NovisLearningPathRepository
{
    public function getList(): NovisLearningPaths
    {
        return new NovisLearningPaths();
    }
}