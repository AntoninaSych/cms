<?php


namespace Edhub\CMS\Containers\LearningPath\UI\Controllers;

use Edhub\CMS\Containers\LearningPath\Application\Actions\GetNovisPathsList\GetNovisLearningPathListAction;
use Edhub\CMS\Containers\LearningPath\UI\Transformers\NovisLearningPathTransformer;
use Throwable;
use Edhub\Shared\UI\BaseApiController;
use Edhub\CMS\Containers\LearningPath\Application\Actions\ArchiveLearningPath\ArchiveLearningPathAction;
use Edhub\CMS\Containers\LearningPath\Application\Actions\ArchiveLearningPath\ArchiveLearningPathTransporter;
use Edhub\Shared\UI\Requests\FilterableRequest;
use Edhub\Shared\Values\PaginationPresenter;
use Edhub\CMS\Containers\LearningPath\Application\Actions\CreateLearningPath\CreateLearningPathAction;
use Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\GetLearningPathListAction;
use Edhub\CMS\Containers\LearningPath\Application\Actions\ShowLearningPath\GetLearningPathAction;
use Edhub\CMS\Containers\LearningPath\Application\Actions\ShowLearningPath\GetLearningPathTransporter;
use Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\GetLearningPathListTransporter;
use Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\UpdateLearningPathAction;
use Edhub\CMS\Containers\LearningPath\UI\Request\CreateLearningPathRequest;
use Edhub\CMS\Containers\LearningPath\UI\Request\UpdateLearningPathRequest;
use Edhub\CMS\Containers\LearningPath\UI\Transformers\LearningPathTransformer;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Support\Facades\DB;

class LearningPathController extends BaseApiController
{
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function store(CreateLearningPathRequest $request, CreateLearningPathAction $action)
    {
        try {

            $transporter = $request->toTransporter();
            DB::beginTransaction();
            $learningPath = $action->run($transporter);
            DB::commit();
            return $this->success([
                'learnpath' => $this->transform($learningPath, LearningPathTransformer::class)
            ]);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['type' => 'educations.path.store.failed']);
            throw $exception;
        }

    }

    public function list(FilterableRequest $request, GetLearningPathListAction $action)
    {
        try {
            $page = $request->get('page', 1);
            $learningPathList = $action->run(new GetLearningPathListTransporter([
                'filters' => $request->filters(),
                'sorting' => $request->sorting(),
                'search' => $request->search(),
                'perPage' => $request->get('perPage')
            ]));
            $paginationOption = new PaginationPresenter($learningPathList->perPage(), $page, $learningPathList->total());
            $meta =  $paginationOption->display();


            return $this->success([
                'learnpaths' => $this->transform($learningPathList, LearningPathTransformer::class, [], $meta),
            ]);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['type' => 'educations.path.list.failed']);
            throw $exception;
        }
    }

    public function show(int $id, GetLearningPathAction $action)
    {
        try {
            $learningPath = $action->run(new GetLearningPathTransporter(['id' => $id]));
            return $this->success([
                'learnpath' => $this->transform($learningPath, LearningPathTransformer::class, ['documents'])
            ]);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['type' => 'educations.path.show.failed']);
            throw $exception;
        }
    }

    public function update(int $id, UpdateLearningPathRequest $request, UpdateLearningPathAction $action)
    {
        try {
            $request->merge(compact('id'));
            $transporter = $request->toTransporter();
            DB::beginTransaction();
            $learningPath = $action->run($transporter);
            DB::commit();

            return $this->success([
                'learnpath' => $this->transform($learningPath, LearningPathTransformer::class)
            ]);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['type' => 'educations.path.update.failed']);
            throw $exception;
        }
    }

    public function archive($id, ArchiveLearningPathAction $action, Logger $logger)
    {

        try {
            $transporter = new ArchiveLearningPathTransporter(compact('id'));
            DB::beginTransaction();
            $action->run($transporter);
            DB::commit();
            $logger->info("LearningPath {$id} is archived.", ['type' => 'learnpaths.archive.success']);

            return $this->success();
        } catch (Throwable $exception) {
            $logger->error($exception->getMessage(), ['type' => 'learnpaths.achive.failed']);
            throw $exception;
        }
    }

    public function listNovies(GetNovisLearningPathListAction $action)
    {
        $novisPaths = $action->run();

        return $this->success([
                "novis_learnpaths" => $this->transform($novisPaths, NovisLearningPathTransformer::class)
            ]
        );
    }
}