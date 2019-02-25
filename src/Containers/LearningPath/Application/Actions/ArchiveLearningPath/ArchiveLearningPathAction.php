<?php

namespace Edhub\CMS\Containers\LearningPath\Application\Actions\ArchiveLearningPath;

use Edhub\CMS\Containers\LearningPath\Domain\Repositories\LearningPathRepository;
use Edhub\CMS\Containers\LearningPath\Domain\Values\LearningPathId;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Status;

class ArchiveLearningPathAction
{

    private $learningPath;

    public function __construct(LearningPathRepository $learningPath)
    {
        $this->learningPath = $learningPath;
    }


    public function run(ArchiveLearningPathTransporter $transporter): void
    {
        $id = (int)$transporter->id;
        $learningPathId = LearningPathId::new($id);
        $learningPath = $this->learningPath->getOne($learningPathId);

        $learningPath->changeStatus(Status::archived());
        $this->learningPath->save($learningPath);
    }
}