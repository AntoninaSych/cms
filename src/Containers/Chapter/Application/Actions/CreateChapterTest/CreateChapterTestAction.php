<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapterTest;

use Edhub\CMS\Containers\Chapter\Domain\Collections\OrderedChapterTestAnswerCollection;
use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTest;
use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterTestRepository;
use Edhub\CMS\Containers\Chapter\Domain\Task\CreateChapterTestAnswerTask;
use Edhub\CMS\Containers\Chapter\Domain\Values\Answer;
use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterId;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestType;

class CreateChapterTestAction
{
    /**
     * @var ChapterTestRepository
     */
    private $chapterTests;
    /**
     * @var CreateChapterTestAnswerTask
     */
    private $createChapterTestAnswer;

    public function __construct(ChapterTestRepository $chapterTests,CreateChapterTestAnswerTask $createChapterTestAnswer)
    {
        $this->chapterTests = $chapterTests;
        $this->createChapterTestAnswer = $createChapterTestAnswer;
    }

    public function run(CreateChapterTestTransporter $input): ChapterTest
    {
        [$chapterId, $title, $type, $correctAnswer, $incorrectAnswer] = [
            (int)$input->chapter, (string)$input->title, $input->type,
            (string)$input->correctAnswer, (string)$input->incorrectAnswer
        ];

        $chapterTest = ChapterTest::create(ChapterId::new($chapterId), $title);
        if (!is_null($type)) {
            $chapterTest->changeType(TestType::new($type));
        }
        $chapterTest->changeCorrectAnswerText($correctAnswer);
        $chapterTest->changeIncorrectAnswerText($incorrectAnswer);
        $this->chapterTests->save($chapterTest);


        $this->saveAnswers($input, $chapterTest);

        return $chapterTest;
    }


    private function saveAnswers(CreateChapterTestTransporter $input, ChapterTest $chapterTest): void
    {
        $orderedAnswerCollection = OrderedChapterTestAnswerCollection::make();
        foreach ($input->answers as $answer) {
            $testAnswer = $this->createChapterTestAnswer->run($chapterTest->id(), Answer::new($answer['answer']),  (bool)$answer['isCorrect']);
            $orderedAnswerCollection->add($testAnswer);
        }

        $chapterTest->syncAnswers($orderedAnswerCollection);
    }
}