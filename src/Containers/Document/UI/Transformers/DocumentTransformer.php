<?php


namespace Edhub\CMS\Containers\Document\UI\Transformers;


use Edhub\CMS\Containers\Document\Domain\Entities\Document;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class DocumentTransformer extends BaseTransformer
{
    public function transform(Document $document): array
    {
        return [
            'id' => $document->id()->value(),
            'title' => $document->title()->value(),
            'description' => $document->description()->value(),
            'type' => $document->type()->value(),
            'roles' => $document->roles()->toScalarList(),
            'learning_path_id' => (!is_null($document->learningPathId())) ? $document->learningPathId()-> value() : null,
            'link'=> $document->link()
        ];
    }
}