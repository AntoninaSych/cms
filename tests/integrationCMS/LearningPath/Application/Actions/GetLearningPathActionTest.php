<?php

namespace Edhub\CMS\tests\integrationCMS\LearningPath\Application\Actions;


use Edhub\CMS\Containers\LearningPath\Application\Actions\ShowLearningPath\GetLearningPathAction;
use Edhub\CMS\Containers\LearningPath\Application\Actions\ShowLearningPath\ShowLearningPathTransporter;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\DatabaseLearningPathRepository;
use Illuminate\Support\Facades\Artisan;

class GetLearningPathActionTest extends \BaseIntegrationTest
{
    /**
     * @var \IntegrationTester
     */
    protected $tester;
    protected $table = 'learning_paths';
    protected $coursesTable = 'courses';
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
    public function get_learning_path_action()
    {
        $action = new GetLearningPathAction($this->learningPathRepository);
        $testData1 = ['id' => '1', 'title' => 'test_key', 'status' => 1, 'points' => 5, 'language' =>'en'];
        $id = 1;
        $transporter = new ShowLearningPathTransporter(['id' => $id]);
        $this->tester->haveRecord($this->table, $testData1);
        $data = $action->run($transporter);
        $this->assertInstanceOf(LearningPath::class, $data);
        $this->assertTrue($data->id() === $id, 'Row with expected id now found');
    }

    //todo Implement Fail scenario
}