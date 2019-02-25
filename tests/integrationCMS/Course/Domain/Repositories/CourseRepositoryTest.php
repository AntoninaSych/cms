<?php

namespace Edhub\CMS\tests\integrationCMS\Course\Application\Domain\Repositories;


use Edhub\CMS\Containers\Course\Domain\Exceptions\CourseNotSaved;
use \Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository;
use \Edhub\CMS\Containers\Course\Domain\Values\CourseId;
use \Edhub\CMS\Containers\Course\Domain\Exceptions\CourseNotFound;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use \Edhub\CMS\Containers\Common\Values\Language;
use \Edhub\CMS\Containers\Course\Domain\Values\CourseStatus;

class CourseRepositoryTest extends \BaseIntegrationTest
{
    /**
     * @var \IntegrationCmsTester
     */
    protected $tester;
    protected $table = "courses";
    protected $repository;

    protected function _before()
    {
        $this->repository = $this->makeService(CourseRepository::class);
    }

    protected function _after()
    {
    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository::getOne
     */
    public function get_one_success_test()
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
        $dataInitial = ['id' => 1, 'title' => 'Initial Title', 'status' => 2, "language" => "nl"];

        $this->tester->haveRecord($this->table, $dataInitial);
        $data = $this->repository->getOne(CourseId::new(1));
        $this->tester->assertTrue($data->title === "Initial Title");
    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository::getOne
     */
    public function get_one_not_found_test()
    {
        try {
            $this->repository->getOne(CourseId::new(1));
        } catch (\Throwable $exception) {
            $this->tester->assertInstanceOf(CourseNotFound::class, $exception);
        }
    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository::save
     */
    public function save_success_test()
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
        $dataInitial = ['title' => 'Initial Title', "language" => "en"];

        $title = 'Initial Title'; //todo Values
        $lang = Language::new('en');
        $Course = Course::create($title, $lang);
        $Course->changeStatus(CourseStatus::published());
        $this->repository->save($Course);
        $this->tester->seeRecord($this->table, $dataInitial);
    }


    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository::save
     */
    public function save_failed_test()
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
        $dataInitial = ['title' => 'Initial Title', "language" => "en"];

        $title = 'Initial Title'; //todo Values
        $lang = Language::new('en');
        $course = Course::create($title, $lang);
        $course->changeStatus(CourseStatus::published());
        $course->setAttribute('non_existed_field', null);
        $this->expectException(CourseNotSaved::class);
        $this->repository->save($course);
        $this->tester->dontSeeRecord($this->table, $dataInitial);
    }


    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Domain\Repositories\CourseRepository::delete
     */
    public function delete_success_test()
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
        $dataInitial = ['title' => 'Initial Title', "language" => "en", "status" => 1];

        $title = 'Initial Title'; //todo Values
        $lang = Language::new('en');
        $course = Course::create($title, $lang);
        $course->changeStatus(CourseStatus::draft());
        $id = $this->tester->haveInDatabase($this->table, $dataInitial);
        $course = $course->setAttribute('id', $id);
        $this->repository->delete($course);

        $this->tester->seeRecord($this->table, $dataInitial);
    }
}