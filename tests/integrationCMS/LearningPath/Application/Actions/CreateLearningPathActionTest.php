<?php
namespace Edhub\CMS\tests\integrationCMS\LearningPath\Application\Actions;

use Edhub\CMS\Containers\LearningPath\Application\Actions\CreateLearningPath\CreateLearningPathAction;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\DatabaseLearningPathRepository;
use Edhub\CMS\Containers\LearningPath\UI\Request\CreateLearningPathRequest;
use Illuminate\Support\Facades\Artisan;

class CreateLearningPathActionTest extends \BaseIntegrationTest
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
     * @covers \Edhub\CMS\Containers\LearningPath\Application\Actions\CreateLearningPath\CreateLearningPathAction::run
     */
    public function create_learning_path_list_action()
    {
        $action = new CreateLearningPathAction($this->learningPathRepository);
        $dataIn = ['title' => 'Some title', 'status' => 1];
        $request = new CreateLearningPathRequest($dataIn);
        $transporter = $request->toTransporter();
        $action->run($transporter);
        $this->tester->seeRecord($this->table, ['title' => 'Some title', 'status' => 1]);
    }

    //todo Implement Fail scenario
}