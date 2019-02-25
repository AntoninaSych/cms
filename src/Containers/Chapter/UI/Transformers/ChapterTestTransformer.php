<?php

namespace Edhub\CMS\Containers\Chapter\UI\Transformers;

use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTest;
use Edhub\Shared\UI\Transformers\BaseTransformer;
use Edhub\CMS\Containers\Chapter\UI\Transformers\ChapterTestAnswerTransformer;

class ChapterTestTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['answers'];

    public function transform(ChapterTest $chapterTest)
    {
        return [
            'id' => $chapterTest->id()->value(),
            'title' => $chapterTest->title(),
            'type' => $chapterTest->type()->value(),
            'correctAnswer' => $chapterTest->correctAnswerText(),
            'incorrectAnswer' => $chapterTest->incorrectAnswerText(),
            'updatedAt' => $chapterTest->wasUpdatedAt()->getTimestamp(),
        ];
    }

    public function includeAnswers(ChapterTest $chapterTest)
    {
        return $this->collection($chapterTest->answers(), new ChapterTestAnswerTransformer());
    }
}