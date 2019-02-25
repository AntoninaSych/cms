<?php

namespace Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapter;

use Edhub\CMS\Containers\Chapter\Domain\Repositories\ChapterRepository;
use Edhub\CMS\Containers\Chapter\Domain\Values\ChapterType;
use Edhub\CMS\Containers\Chapter\Domain\Entities\Chapter;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;

class CreateChapterAction
{
    /**
     * @var ChapterRepository
     */
    private $chapters;

    public function __construct(ChapterRepository $chapters)
    {
        $this->chapters = $chapters;
    }

    public function run(CreateChapterTransporter $input): Chapter
    {
        [$courseId, $title, $subtitle, $content, $type, $extraLink] = [
            $input->course, (string)$input->title, (string)$input->subtitle,
            $input->content, (int)$input->type, (string)$input->extraLink
        ];

        $chapter = Chapter::create($title, CourseId::new($courseId));
        $chapter->changeSubtitle($subtitle);
        $chapter->changeContent($content);
        $chapter->changeType(ChapterType::new($type));
        $chapter->changeExtraLink($extraLink);

        $this->chapters->save($chapter);

        return $chapter;
    }
}