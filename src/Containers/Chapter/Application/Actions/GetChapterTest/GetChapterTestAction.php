<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\GetChapterTest;

use Edhub\CMS\Containers\Chapter\Domain\Entities\ChapterTest;
use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterTestRepository;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestId;

class GetChapterTestAction
{
    /**
     * @var ChapterTestRepository
     */
    private $chapterTests;

    public function __construct(ChapterTestRepository $chapterTests)
    {
        $this->chapterTests = $chapterTests;
    }

    public function run(GetChapterTestTransporter $transporter): ChapterTest
    {
        $chapterTestId = TestId::new((int)$transporter->id);
        $chapterTest = $this->chapterTests->getOne($chapterTestId);

        return $chapterTest;
    }
}