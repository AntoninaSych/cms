<?php
namespace Edhub\CMS\tests\integrationCMS\Course\Application\Actions;

use Edhub\CMS\Containers\Course\Application\Actions\GetCourseStatusList\GetCourseStatusListAction;
use Edhub\CMS\Containers\Course\Domain\Values\CourseStatus;

class GetCourseStatusListActionTest extends \BaseIntegrationTest
{
    /**
     * @var \IntegrationCmsTester
     */
    protected $tester;
    protected $action;
    protected $table = 'courses';
    protected function _before()
    {
        $this->action = $this->makeService(GetCourseStatusListAction::class);
    }

    protected function _after()
    {
        $this->action = null;
    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Application\Actions\GetCourseStatusList\GetCourseStatusListAction::run
     */
    public function get_course_status_list_success()
    {
        $data = $this->action->run();
        $this->tester->assertTrue(true, in_array(CourseStatus::new(1),$data));
        $this->tester->assertTrue(true, in_array(CourseStatus::new(2),$data));
        $this->tester->assertTrue(true, in_array(CourseStatus::new(3),$data));
    }
}