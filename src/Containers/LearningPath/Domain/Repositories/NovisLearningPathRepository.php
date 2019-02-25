<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Repositories;

use Edhub\CMS\Containers\LearningPath\Domain\Collection\NovisLearningPaths;

interface NovisLearningPathRepository
{
    public function getList(): NovisLearningPaths;
}
