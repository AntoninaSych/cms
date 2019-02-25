<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Collections;


use Edhub\Shared\Assertions\Assertion;
use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTestAnswer;
use Edhub\CMS\Containers\Chapter\Domain\Values\AnswerPosition;

class OrderedChapterTestAnswerCollection extends ChapterTestAnswerCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct($elements);
        $position = 1;
        /** @var ChapterTestAnswer $answerTest */
        foreach ($this->getValues() as $answerTest) {
            $answerTest->changePosition(AnswerPosition::new($position));
            $position++;
        }
    }

    /**
     * @param ChapterTestAnswer $answer
     * @return bool
     */
    public function add($answer)
    {
        Assertion::isInstanceOf($answer, ChapterTestAnswer::class);
        $position = $this->count() + 1;
        $answer->changePosition(AnswerPosition::new($position));

        return parent::add($answer);
    }
}