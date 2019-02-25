<?php

namespace Edhub\CMS\Containers\Chapter\UI\Transformers;

use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterType;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class ChapterTypeTransformer extends BaseTransformer
{
    public function transform(ChapterType $chapterType)
    {
        return [
            'value' => $chapterType->value(),
        ];
    }
}