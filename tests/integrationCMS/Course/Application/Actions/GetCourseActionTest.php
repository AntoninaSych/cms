<?php

namespace Edhub\CMS\tests\integrationCMS\Course\Application\Actions;

use App\Containers\Authentication\Data\Transporters\CompanyTransporter;
use Edhub\CMS\Containers\Course\Application\Actions\GetCourse\GetCourseTransporter;
use Edhub\CMS\Containers\Course\Application\Actions\GetCourse\GetCourseAction;
use Edhub\CMS\Containers\Course\Domain\Collections\Categories;
use Edhub\CMS\Containers\Course\Domain\Collections\CourseCompanies;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Edhub\CMS\Containers\Course\Domain\Values\Language;
use Edhub\CMS\Containers\Course\UI\Requests\CreateCourseRequest;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Factory;

class GetCourseActionTest extends \BaseIntegrationTest
{
    /**
     * @var \IntegrationCmsTester
     */
    protected $tester;
    protected $table = "courses";
    protected $action;
    protected $provider;

    protected function _before()
    {
        $this->action = $this->makeService(GetCourseAction::class);
    }

    protected function _after()
    {
        $this->action = null;
    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Application\Actions\GetCourse\GetCourseAction::run
     */
    public function GetCourseActionTest()
    {
        $dataIn = ['id' => 1, 'title' => 'Some title', 'status' => 1, "language" => "en"];
        $this->tester->haveRecord($this->table, $dataIn);
        $id = 1;
        $this->action->run(new GetCourseTransporter(compact('id')));
        $this->tester->seeRecord($this->table, ['title' => 'Some title', 'status' => 1, "language" => "en"]);
    }

    //todo Implement Fail scenario
}