<?php

namespace Edhub\CMS\tests\integrationCMS\Course\Application\Actions;

use Edhub\CMS\Containers\Course\Application\Actions\DeleteCourse\DeleteCourseTransporter;
use Edhub\CMS\Containers\Course\Application\Actions\DeleteCourse\DeleteCourseAction;
use Illuminate\Support\Facades\Artisan;

class DeleteCourseActionTest extends \BaseIntegrationTest
{
    /**
     * @var \IntegrationCmsTester
     */
    protected $tester;
    protected $table = "courses";
    protected $action;

    protected function _before()
    {
        $this->action = $this->makeService(DeleteCourseAction::class);
    }

    protected function _after()
    {
        $this->action = null;
    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Application\Actions\DeleteCourse\DeleteCourseAction::run
     */
    public function delete_course_action()
    {
        $dataIn = [  'title' => 'Some title', 'status' => 1, "language" => "en"];
        $id = $this->tester->haveRecord($this->table, $dataIn);
        $this->action->run(new DeleteCourseTransporter(compact('id')));
        $this->tester->dontSeeRecord($this->table, $dataIn);
    }

    //todo Implement Fail scenario
}