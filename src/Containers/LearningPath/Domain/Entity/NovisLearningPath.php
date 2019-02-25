<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Entity;
use Illuminate\Database\Eloquent\Model;
use Edhub\CMS\Containers\LearningPath\Domain\Values\NovisLearningPathId;

class NovisLearningPath
{
    /** @var NovisLearningPathId */
    private $id;
    /** @var string */
    private $title;

    private function __construct(NovisLearningPathId $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }


    public static function new(NovisLearningPathId $id, string $title) :NovisLearningPath
    {
        $novisPath = new self($id,$title);
        return $novisPath;
    }

    /**
     * @return NovisLearningPathId
     */
    public function id(): NovisLearningPathId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
}