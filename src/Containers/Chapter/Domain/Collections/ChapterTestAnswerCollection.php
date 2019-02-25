<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Collections;


use Doctrine\Common\Collections\ArrayCollection;
use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTestAnswer;
use Edhub\CMS\Containers\Chapter\Domain\Values\AnswerId;
use Edhub\Shared\Assertions\Assertion;

class ChapterTestAnswerCollection extends ArrayCollection
{
    public function __construct(array $elements = [])
    {
        Assertion::allIsInstanceOf($elements, ChapterTestAnswer::class);
        parent::__construct($elements);
    }

    public static function make(array $chapters = []): ChapterTestAnswerCollection
    {
        return new static($chapters);
    }

    public function hasAnswer(int $id): bool
    {
        return !is_null($this->findById($id));
    }

    public function findById(int $id): ?ChapterTestAnswer
    {
        $answerId = AnswerId::new($id);
        $filteredAnswers = $this->filter(function (ChapterTestAnswer $chapterTestAnswer) use ($answerId) {
            return $chapterTestAnswer->id()->isEqualTo($answerId);
        });

        return $filteredAnswers->first() ?: null;
    }
}