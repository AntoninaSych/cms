<?php

namespace Edhub\CMS\tests\integrationCMS\Course\Application\Actions;

use Edhub\Shared\Collections\PaginatedCollection;
use Edhub\Shared\UI\Requests\FilterableRequest;
use Edhub\CMS\Containers\Course\Application\Actions\GetCourseList\GetCourseListAction;
use Edhub\CMS\Containers\Course\Application\Actions\GetCourseList\GetCourseListTransporter;



class GetCourseListActionTest extends \BaseIntegrationTest
{
    /**
     * @var \IntegrationCmsTester
     */
    protected $tester;
    protected $table = "courses";
    protected $action;

    protected function _before()
    {
        $this->action = $this->makeService(GetCourseListAction::class);
    }

    protected function _after()
    {
        $this->action = null;
    }

    /**
     * @test
     * @covers \Edhub\CMS\Containers\Course\Application\Actions\GetCourseList\GetCourseListAction::run
     */
    public function get_course_list_action()
    {
        $dataIn = [
            ['id' => 0, 'title' => 'Some title', 'status' => 1, "language" => "en"],
            ['id' => 1, 'title' => 'Daxx title', 'status' => 1, "language" => "en"]
        ];
        $this->tester->haveRecord($this->table, $dataIn[0]);
        $this->tester->haveRecord($this->table, $dataIn[1]);

        $request = $this->makeService(FilterableRequest::class, ['page' => 1]);
        $page = $request->get('page', 1);
        $data = $this->action->run(new GetCourseListTransporter([
            'filters' => $request->filters(),
            'sorting' => $request->sorting(),
            'perPage' => $request->get('perPage')
        ]));

        $this->assertInstanceOf(PaginatedCollection::class, $data);
    }
}