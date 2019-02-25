<?php


namespace Edhub\CMS\tests\integrationCMS\Course\Application\Actions;


use Edhub\Shared\Exceptions\DomainException;
use Edhub\CMS\Containers\Course\Application\Actions\UpdateCourse\UpdateCourseAction;
use Edhub\CMS\Containers\Course\UI\Requests\UpdateCourseRequest;

class UpdateCourseActionTest extends \BaseIntegrationTest
{
    /**
     * @var \IntegrationCmsTester
     */
    protected $tester;
    protected $table = "courses";
    protected $action;

    protected function _before()
    {
        $this->action = $this->makeService(UpdateCourseAction::class);
    }

    protected function _after()
    {
        $this->action = null;
    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Application\Actions\UpdateCourse\UpdateCourseAction::run
     */
    public function course_updated_successful()
    {
        $this->tester->haveRecord("companies",
            [
                'id' => 2,
                'name' => "Daxx Company",
                "status" => 1,
                "lft" => 1,
                "rgt" => 1
            ]
        );
        $dataInitial = ['id' => 1, 'title' => 'Old Title', 'status' => 2, "language" => "nl"];

        $id = $this->tester->haveRecord($this->table, $dataInitial);

        $dataIn = ['id' => $id, 'title' => 'Some title', 'status' => 1, "language" => "en", "description" => [],
            "companies" => [["company" => 2, "isPublic" => true]]
        ];

        $request = new UpdateCourseRequest($dataIn);
        $transporter = $request->toTransporter();
        $this->action->run($transporter);
        $this->tester->seeRecord($this->table, ['title' => 'Some title', 'status' => 1]);
        $this->tester->dontSeeRecord($this->table, ['title' => 'Old Title', 'status' => 2]);
    }


    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Application\Actions\UpdateCourse\UpdateCourseAction::run
     */
    public function course_updated_fail_validation()
    {
        $this->tester->haveRecord("companies",
            [
                'id' => 2,
                'name' => "Daxx Company",
                "status" => 1,
                "lft" => 1,
                "rgt" => 1
            ]
        );
        $dataInitial = ['id' => 1, 'title' => 'Old Initial Title', 'status' => 2, "language" => "nl"];

        $this->tester->haveRecord($this->table, $dataInitial);

        $dataIn = ['id' => 1, 'title' => 'Some new title', 'status' => 7, "language" => "ua", "description" => "",
            "companies" => [["company" => 2, "isPublic" => true]]
        ];

        $request = new UpdateCourseRequest($dataIn);
        $transporter = $request->toTransporter();

        try {
            $this->action->run($transporter);
        } catch (DomainException $e) {
            $this->assertInstanceOf(DomainException::class, $e);
        }

        $this->tester->dontSeeRecord($this->table, ['title' => 'Some new title', 'status' => 7]);
        $this->tester->seeRecord($this->table, ['title' => 'Old Initial Title', 'status' => 2]);
    }
}