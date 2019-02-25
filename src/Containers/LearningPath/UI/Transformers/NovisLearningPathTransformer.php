<?php

namespace Edhub\CMS\Containers\LearningPath\UI\Transformers;

use Edhub\CMS\Containers\LearningPath\Domain\Entity\NovisLearningPath;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class NovisLearningPathTransformer extends BaseTransformer
{
    public function transform(NovisLearningPath $learningPath): array
    {
        return [
            'id' => $learningPath->id()->value(),
            'title' => $learningPath->title()
        ];
    }
}

