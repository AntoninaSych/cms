<?php

namespace Edhub\CMS\Containers\Course\UI\Transformers;

use Edhub\CMS\Containers\Course\Domain\Values\CourseStatus;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class CourseStatusTransformer extends BaseTransformer
{
    public function transform(CourseStatus $status): array
    {
        return [
            'value' => $status->value(),
        ];
    }
}