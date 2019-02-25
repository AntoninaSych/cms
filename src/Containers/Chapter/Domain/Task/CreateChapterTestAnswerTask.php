<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Task;

use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTestAnswer;
use Edhub\CMS\Containers\Chapter\Domain\Values\Answer;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestId;

class CreateChapterTestAnswerTask
{
    public function run(TestId $testId, Answer $answer, bool $isCorrect): ChapterTestAnswer
    {
        return ChapterTestAnswer::create($answer, $isCorrect, $testId);
    }
}