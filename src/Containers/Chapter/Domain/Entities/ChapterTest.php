<?php

namespace Edhub\CMS\Containers\Chapter\Domain\Entities;

use App\Ship\Parents\Models\Model;
use Edhub\Shared\Assertions\Assertion;
use Edhub\CMS\Containers\Chapter\Domain\Collections\ChapterTestAnswerCollection;
use Edhub\CMS\Containers\Chapter\Domain\Exception\FewAnswersForTest;
use Edhub\CMS\Containers\Chapter\Domain\Exception\InvalidTestAnswersNumber;
use Edhub\CMS\Containers\Chapter\Domain\Values\AnswerPosition;
use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterId;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestId;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestType;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class ChapterTest extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'position',
        'sort_when_creating' => true,
    ];
    protected $table = 'course_chapter_tests';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    public static function create(ChapterId $chapter, string $title)
    {
        $test = new self;
        $test->setAttribute('chapter_id', $chapter->value());
        $test->changeTitle($title);
        $test->changeType(TestType::singleAnswer());

        return $test;
    }

    public function id(): TestId
    {
        return TestId::new((int)$this->getAttributeValue('id'));
    }

    public function title(): string
    {
        return (string)$this->getAttributeValue('title');
    }

    /**
     * @param string $title
     * @throws \Assert\AssertionFailedException
     */
    public function changeTitle(string $title): void
    {
        Assertion::notEmpty($title, 'Test title is empty.');
        $this->setAttribute('title', $title);
    }

    public function subtitle(): string
    {
        return (string)$this->getAttributeValue('subtitle');
    }

    /**
     * @param string $subtitle
     * @throws \Assert\AssertionFailedException
     */
    public function changeSubtitle(string $subtitle): void
    {
        $this->setAttribute('subtitle', $subtitle);
    }

    public function type(): TestType
    {
        return TestType::new((int)$this->getAttributeValue('type'));
    }

    public function changeType(TestType $type): void
    {
        $this->setAttribute('type', $type->value());
    }

    public function position(): AnswerPosition
    {
        return AnswerPosition::new($this->getAttributeValue('position'));
    }

    public function changePosition(int $position): void
    {
        Assertion::notEmpty($position, 'Test position is zero.');
        $this->setAttribute('position', $position);
    }

    public function correctAnswerText(): string
    {
        return (string)$this->getAttributeValue('answer_correct');
    }

    public function changeCorrectAnswerText(string $answer): void
    {
        $this->setAttribute('answer_correct', $answer);
    }

    public function incorrectAnswerText(): string
    {
        return (string)$this->getAttributeValue('answer_incorrect');
    }

    public function changeIncorrectAnswerText(string $answer): void
    {
        $this->setAttribute('answer_incorrect', $answer);
    }

    public function syncAnswers(ChapterTestAnswerCollection $answers): void
    {
        $this->validateAnswers($answers);

        $testAnswers = iterator_to_array($this->answer_relation);
        $inputAnswers = iterator_to_array($answers);

        $answersToRemove = array_diff($testAnswers, $inputAnswers);
        foreach ($answersToRemove as $answer) {
            $answer->delete();
        }

        $this->answer_relation()->saveMany($inputAnswers);
        $this->load('answer_relation');
    }

    public function answers(): ChapterTestAnswerCollection
    {
        return ChapterTestAnswerCollection::make(
            $this->answer_relation->all()
        );
    }


    public function validateAnswers(ChapterTestAnswerCollection $answers)
    {
        $answers = iterator_to_array($answers);
        if (count($answers) < 1) {
            throw new FewAnswersForTest();
        }

        $answerIsCorrect = array_filter($answers, function (ChapterTestAnswer $answer) {
            return $answer->isCorrect();
        });

        if (count($answerIsCorrect) === 0) {
            throw new InvalidTestAnswersNumber();
        }
        if ($this->type()->isEqualTo(TestType::singleAnswer()) && count($answerIsCorrect) !== 1) {
            throw new InvalidTestAnswersNumber('Self test has single answer type');
        }
    }

    public function answer_relation()
    {
        return $this->hasMany(
            ChapterTestAnswer::class,
            'course_chapter_test_id',
            'id'
        )->orderBy('position');
    }

    public function wasUpdatedAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            (string)$this->getAttribute('updated_at')
        );
    }

}