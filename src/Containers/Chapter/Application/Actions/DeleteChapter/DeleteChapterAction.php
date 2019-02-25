<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\DeleteChapter;

use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterRepository;
use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterId;

class DeleteChapterAction
{
    /**
     * @var ChapterRepository
     */
    private $chapters;

    public function __construct(ChapterRepository $chapters)
    {
        $this->chapters = $chapters;
    }

    public function run(DeleteChapterTransporter $transporter)
    {
        $chapterId = ChapterId::new($transporter->id);
        $chapter = $this->chapters->getOne($chapterId);
        $this->chapters->remove($chapter);
    }
}