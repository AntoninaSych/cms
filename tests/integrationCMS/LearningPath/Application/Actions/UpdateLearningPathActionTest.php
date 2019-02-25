<?php

namespace Edhub\CMS\tests\integration\LearningPath\Application\Actions;


use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\DatabaseLearningPathRepository;
use Edhub\CMS\Containers\LearningPath\Domain\Values\Title;
use Illuminate\Support\Facades\Artisan;
use Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\UpdateLearningPathTransporter;
use Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\UpdateLearningPathAction;

class UpdateLearningPathActionTest extends \BaseIntegrationTest
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
     * @covers  \Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\UpdateLearningPathAction::run
     */
    public function update_learning_path_action()
    {
        $action = new UpdateLearningPathAction($this->learningPathRepository);
        $testData1 = ['id' => '1', 'title' => 'test_key', 'status' => 1, 'points' => 5,'language' => 'en'];
        $this->tester->haveRecord($this->table, $testData1);
        $testData2 = ['id' => '1', 'title' => 'new_key', 'status' => 2, 'points' => 6,'language' => 'nl'];
        $transporter = new UpdateLearningPathTransporter($testData2);
        $data = $action->run($transporter);
        $this->tester->seeRecord($this->table, $testData2);
        $this->assertInstanceOf(LearningPath::class, $data);
        $this->assertTrue($data->title()->value() === Title::new('new_key')->value());
    }

    //todo Implement Fail scenario
}