<?php

namespace Edhub\CMS\tests\integration\LearningPath\Application\Actions;

use Edhub\Shared\Collections\PaginatedCollection;
use Edhub\Shared\Criteria\CriteriaFactory;
use Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\GetLearningPathListAction;
use Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\GetLearningPathListTransporter;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\DatabaseLearningPathRepository;



class GetLearningPathListActionTest extends \BaseIntegrationTest
{
    /**
     * @var \IntegrationTester
     */
    protected $tester;
    protected $table = 'learning_paths';
    protected $learningPathRepository;

    protected function _before()
    {
        $this->learningPathRepository = $this->makeService(DatabaseLearningPathRepository::class);
    }

    protected function _after()
    {
        $this->tester = null;
        $this->learningPathRepository = null;
    }

    /**
     * @test
     * @covers  \Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\GetLearningPathAction::run
     */
    public function Get_learning_path_list_action()
    {
        $criteria = new CriteriaFactory();
        $action = new GetLearningPathListAction($this->learningPathRepository, $criteria);

        $testData1 = ['title' => 'test_key', 'status' => 1, 'points' => 5, 'language' => 'en'];
        $this->tester->haveRecord($this->table, $testData1);
        $transporter = new GetLearningPathListTransporter();
        $data = $action->run($transporter);
        $this->assertInstanceOf(PaginatedCollection::class, $data);
    }

    //todo Implement Fail scenario
}
