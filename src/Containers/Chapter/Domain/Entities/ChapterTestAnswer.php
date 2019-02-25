<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Entities;

use App\Ship\Parents\Models\Model;
use Edhub\CMS\Containers\Chapter\Domain\Values\Answer;
use Edhub\CMS\Containers\Chapter\Domain\Values\AnswerId;
use Edhub\CMS\Containers\Chapter\Domain\Values\AnswerPosition;
use Edhub\CMS\Containers\Chapter\Domain\Values\IsCorrect;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestId;

class ChapterTestAnswer extends Model
{
    protected $table = 'course_chapter_test_answers';
    public $timestamps = false;
    private const POSITION_DEFAULT = 1;

    public static function create(Answer $answer, bool $isCorrect, TestId $testId)
    {
        $chapterTestAnswer = new self();
        $chapterTestAnswer->changeAnswer($answer);
        $chapterTestAnswer->changeIsCorrect($isCorrect);
        $chapterTestAnswer->changeChapterTestId($testId);
        $chapterTestAnswer->changePosition(AnswerPosition::new(self::POSITION_DEFAULT));
        return $chapterTestAnswer;
    }

    public function changePosition(AnswerPosition $position)
    {
        $this->setAttribute('position', $position->value());
    }

    public function changeAnswer(Answer $answer)
    {
        $this->setAttribute('answer', $answer->value());
    }

    public function changeIsCorrect(bool $isCorrect)
    {
        $this->setAttribute('is_correct', $isCorrect);
    }

    public function changeChapterTestId(TestId $testId)
    {
        $this->setAttribute('course_chapter_test_id', $testId->value());
    }

    public function answer(): Answer
    {
        return Answer::new((string)$this->getAttributeValue('answer'));
    }
    public function isCorrect(): bool
    {
        return  (bool)$this->getAttributeValue('is_correct');
    }

    public function id(): AnswerId
    {
        return AnswerId::new((int)$this->getAttributeValue('id'));
    }

    public function position(): AnswerPosition
    {
        return AnswerPosition::new($this->getAttributeValue('position'));
    }
}