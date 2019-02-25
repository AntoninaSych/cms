<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\DeleteChapterTest;

use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterTestRepository;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestId;

class DeleteChapterTestAction
{
    /**
     * @var ChapterTestRepository
     */
    private $chapterTests;

    public function __construct(ChapterTestRepository $chapterTests)
    {
        $this->chapterTests = $chapterTests;
    }

    public function run(DeleteChapterTestTransporter $transporter): void
    {
        $chapterTestId = TestId::new($transporter->id);
        $chapterTest = $this->chapterTests->getOne($chapterTestId);

        $this->chapterTests->remove($chapterTest);
    }
}