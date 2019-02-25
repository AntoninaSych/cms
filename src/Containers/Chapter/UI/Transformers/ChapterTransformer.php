<?php

namespace Edhub\CMS\Containers\Chapter\UI\Transformers;

use Edhub\CMS\Containers\Chapter\Domain\Entities\Chapter;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class ChapterTransformer extends BaseTransformer
{
    protected $availableIncludes = ['tests', 'children'];

    public function transform(Chapter $chapter)
    {
        return [
            'id' => $chapter->id()->value(),
            'title' => $chapter->title(),
            'subtitle' => $chapter->subtitle(),
            'content' => $chapter->content(),
            'type' => $chapter->type()->value(),
            'position' => $chapter->position(),
            'extraLink' => $chapter->extraLink(),
            'updatedAt' => $chapter->wasUpdatedAt()->getTimestamp(),
        ];
    }

    public function includeChildren(Chapter $chapter)
    {
        return $this->collection($chapter->children(), new ChapterTransformer());
    }

    public function includeTests(Chapter $chapter)
    {
        return $this->collection($chapter->tests(), new ChapterTestTransformer());
    }
}