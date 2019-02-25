<?php


namespace Edhub\CMS\Containers\LearningPath\Domain\Collection;

use Edhub\Shared\Assertions\Assertion;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\NovisLearningPath;
use Doctrine\Common\Collections\ArrayCollection;


class NovisLearningPaths extends ArrayCollection
{
    public function __construct(array $items = [])
    {
        Assertion::allIsInstanceOf($items, NovisLearningPath::class);

        parent::__construct($items);
    }
    public static function make(array $noviPath = []): NovisLearningPaths
    {
        return new self($noviPath);
    }

}