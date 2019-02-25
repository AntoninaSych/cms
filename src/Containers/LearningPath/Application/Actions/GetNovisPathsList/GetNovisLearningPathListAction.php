<?php
namespace Edhub\CMS\Containers\LearningPath\Application\Actions\GetNovisPathsList;

use Edhub\CMS\Containers\LearningPath\Domain\Collection\NovisLearningPaths;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\NovisLearningPathRepository;

class GetNovisLearningPathListAction
{
    protected $novisPath;

    public function __construct(NovisLearningPathRepository $novisLearningPathRepository)
    {
        $this->novisPath = $novisLearningPathRepository;
    }

    public function run():NovisLearningPaths
    {
         $collectionNoviPaths = $this->novisPath->getList();

         return $collectionNoviPaths;
    }

}