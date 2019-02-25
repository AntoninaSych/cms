<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\UpdateChapterTest;

use Edhub\CMS\Containers\Chapter\Domain\Collections\ChapterTestAnswerCollection;
use Edhub\CMS\Containers\Chapter\Domain\Collections\OrderedChapterTestAnswerCollection;
use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTest;
use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterTestRepository;
use Edhub\CMS\Containers\Chapter\Domain\Task\CreateChapterTestAnswerTask;
use Edhub\CMS\Containers\Chapter\Domain\Values\Answer;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestId;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestType;

class UpdateChapterTestAction
{
    /**
     * @var ChapterTestRepository
     */
    private $chapterTests;
    /**
     * @var CreateChapterTestAnswerTask
     */
    private $createChapterTestAnswer;

    public function __construct(ChapterTestRepository $chapterTests, CreateChapterTestAnswerTask $createChapterTestAnswer)
    {
        $this->chapterTests = $chapterTests;
        $this->createChapterTestAnswer = $createChapterTestAnswer;
    }

    public function run(UpdateChapterTestTransporter $input): ChapterTest
    {
        [$chapterTestId, $title, $type, $correctAnswer, $incorrectAnswer, $inputAnswers] = [
            (int)$input->id, (string)$input->title, $input->type, (string)$input->correctAnswer,
            (string)$input->incorrectAnswer, (array)$input->answers
        ];

        $chapterTest = $this->chapterTests->getOne(TestId::new($chapterTestId));

        $chapterTest->changeTitle($title);
        if (!is_null($type)) {
            $chapterTest->changeType(TestType::new($type));
        }
        $chapterTest->changeCorrectAnswerText($correctAnswer);
        $chapterTest->changeIncorrectAnswerText($incorrectAnswer);
        $this->chapterTests->save($chapterTest);
        $orderedAnswerCollection = OrderedChapterTestAnswerCollection::make();

        foreach ($inputAnswers as $inputAnswer) {

            $this->processTestAnswers($inputAnswer, $chapterTest, $orderedAnswerCollection);
        }


        $chapterTest->syncAnswers($orderedAnswerCollection);

        return $chapterTest;
    }

    private function processTestAnswers(array $inputAnswer, ChapterTest $chapterTest, ChapterTestAnswerCollection $orderedAnswerCollection): void
    {
        $inputAnswerId = $inputAnswer['id'] ?? null;
        $inputAnswerText = $inputAnswer['answer'] ?? '';
        $inputAnswerIsCorrect = $inputAnswer['isCorrect'] ?? false;
        $testAnswers = $chapterTest->answers();
        $testHasAnswer = !empty($inputAnswerId) && $testAnswers->hasAnswer($inputAnswerId);
        if ($testHasAnswer) {
            $testAnswer = $testAnswers->findById($inputAnswerId);
            $testAnswer->changeAnswer(Answer::new($inputAnswerText));
            $testAnswer->changeIsCorrect($inputAnswerIsCorrect);
            $orderedAnswerCollection->add($testAnswer);
        }

        if (empty($inputAnswerId)) {
            $testAnswer = $this->createChapterTestAnswer->run($chapterTest->id(), Answer::new($inputAnswerText), $inputAnswerIsCorrect);
            $orderedAnswerCollection->add($testAnswer);
        }
    }
}