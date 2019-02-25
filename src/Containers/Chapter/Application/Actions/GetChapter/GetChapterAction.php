<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\GetChapter;


use Edhub\CMS\Containers\Chapter\Domain\Entities\Chapter;
use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterRepository;
use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterId;

class GetChapterAction
{
    /**
     * @var ChapterRepository
     */
    private $chapters;

    public function __construct(ChapterRepository $chapters)
    {
        $this->chapters = $chapters;
    }

    public function run(GetChapterTransporter $transporter): Chapter
    {
        $id = ChapterId::new($transporter->id);
        $chapter = $this->chapters->getOne($id);

        return $chapter;
    }
}