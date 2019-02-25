<?php


namespace Edhub\CMS\Containers\Chapter\UI\Transformers;


use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTestAnswer;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class ChapterTestAnswerTransformer extends BaseTransformer
{
    public function transform(ChapterTestAnswer $chapterTestAnswer)
    {
        return [
            'id' => $chapterTestAnswer->id()->value(),
            'isCorrect' => $chapterTestAnswer->isCorrect(),
            'answer' => (string)$chapterTestAnswer->answer(),
            'position' =>(int) $chapterTestAnswer->position()->value()
        ];
    }
}


