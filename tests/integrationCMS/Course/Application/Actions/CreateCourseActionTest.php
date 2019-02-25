<?php

namespace Edhub\CMS\tests\integrationCMS\Course\Application\Actions;


use Edhub\Shared\Exceptions\DomainException;
use Edhub\CMS\Containers\Course\UI\Requests\CreateCourseRequest;
use Edhub\CMS\Containers\Course\Application\Actions\CreateCourse\CreateCourseAction;


class CreateCourseActionTest extends \BaseIntegrationTest
{
    /**
     * @var \IntegrationCmsTester
     */
    protected $tester;
    protected $table = "courses";
    protected $action;


    protected function _before()
    {
        $this->action = $this->makeService(CreateCourseAction::class);
    }

    protected function _after()
    {
        $this->action = null;
    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Application\Actions\CreateCourse\CreateCourseAction::run
     */
    public function course_created_successful()
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
        $dataIn = ['title' => 'Some title', 'status' => 1, "language" => "en",
            "companies" => [["company" => 2, "isPublic" => true]]
        ];

        $request = new CreateCourseRequest($dataIn);
        $transporter = $request->toTransporter();
        $this->action->run($transporter);
        $this->tester->seeRecord($this->table, ['title' => 'Some title', 'status' => 1]);

    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Application\Actions\CreateCourse\CreateCourseAction::run
     */
    public function exception_shown_when_create_course_with_wrong_status_param()
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
        $dataIn = ['title' => 'Some title', 'status' => 'wring param', "language" => "en",
            "companies" => [["company" => 2, "isPublic" => true]]
        ];
        $request = new CreateCourseRequest($dataIn);
        $transporter = $request->toTransporter();
        $this->expectException(DomainException::class);
        $this->action->run($transporter);
        $this->tester->seeRecord($this->table, ['title' => 'Some title', 'status' => 1]);

    }


    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Application\Actions\CreateCourse\CreateCourseAction::run
     */
    public function exception_shown_when_create_course_with_empty_courses_param()
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
        $dataIn = ['title' => 'Some title', 'status' => 1, "language" => "en", "companies" => []];

        $request = new CreateCourseRequest($dataIn);
        $transporter = $request->toTransporter();
        $this->expectException(DomainException::class);
        $this->action->run($transporter);
        $this->tester->seeRecord($this->table, ['title' => 'Some title', 'status' => 1]);
    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Application\Actions\CreateCourse\CreateCourseAction::run
     */
    public function exception_shown_when_create_course_with_wrong_title_param()
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
        $dataIn = ['title' => "", 'status' => 1, "language" => "en", "companies" => []];
        $request = new CreateCourseRequest($dataIn);
        $transporter = $request->toTransporter();
        $this->expectException(DomainException::class);
        $this->action->run($transporter);
        $this->tester->seeRecord($this->table, ['title' => '', 'status' => 1]);
    }
}