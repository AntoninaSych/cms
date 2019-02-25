<?php

namespace Edhub\CMS;

use Edhub\CMS\Containers\Document\Domain\Entities\Document;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class CmsLearningPathDocumentTransformer extends BaseTransformer
{
    public function transform(Document $document): array
    {
        return [
//            'id' => $document->id()->value(),
            'title' => sprintf('%s.%s', $document->title()->value(), $document->extension()),
            'description' => $document->description()->value(),
            'link' => $document->link(),
        ];
    }
}