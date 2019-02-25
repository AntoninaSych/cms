<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\UpdateChapter;

use Edhub\CMS\Containers\Chapter\Domain\Entities\Chapter;
use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterRepository;
use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterTestRepository;
use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterId;
use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterType;
use Edhub\CMS\Containers\Chapter\Domain\Values\TestId;

class UpdateChapterAction
{
    /**
     * @var ChapterRepository
     */
    private $chapters;
    /**
     * @var ChapterTestRepository
     */
    private $chapterTests;

    public function __construct(ChapterRepository $chapters, ChapterTestRepository $chapterTests)
    {
        $this->chapters = $chapters;
        $this->chapterTests = $chapterTests;
    }

    public function run(UpdateChapterTransporter $input): Chapter
    {
        [$id, $title, $subtitle, $content, $type, $extraLink, $chapterRawTestIds] = [
            (int)$input->id, (string)$input->title, (string)$input->subtitle, $input->content,
            (int)$input->type, (string)$input->extraLink, $input->tests
        ];

        $chapter = $this->chapters->getOne(ChapterId::new($id));
        $chapter->changeTitle($title);
        $chapter->changeSubtitle($subtitle);
        $chapter->changeContent($content);
        $chapter->changeType(ChapterType::new($type));
        $chapter->changeExtraLink($extraLink);

        //@TODO extract to function or move check inside replaceTests()
        if ($chapter->type()->isEqualTo(ChapterType::selfTest())) {
            $chapterTestIds = array_map([TestId::class, 'new'], $chapterRawTestIds);
            $testsList = $this->chapterTests->findByIds(...$chapterTestIds);
            $chapter->updateTestsPosition($testsList);
        }

        $this->chapters->save($chapter);

        return $chapter;
    }
}