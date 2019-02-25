<?php

namespace Edhub\CMS\Containers\Document\UI\Controllers;

use Edhub\Shared\UI\BaseApiController;
use Edhub\Shared\UI\Requests\Request;
use Edhub\CMS\Containers\Document\Application\Actions\CreateDocument\CreateDocumentAction;
use Edhub\CMS\Containers\Document\Application\Actions\CreateDocument\CreateDocumentTransporter;
use Edhub\CMS\Containers\Document\Application\Actions\DeleteDocuments\DeleteDocumentsAction;
use Edhub\CMS\Containers\Document\Application\Actions\DeleteDocuments\DeleteDocumentTransporter;
use Edhub\CMS\Containers\Document\Application\Actions\GetDocuments\GetDocumentsAction;
use Edhub\CMS\Containers\Document\Application\Actions\GetDocuments\GetDocumentsTransporter;
use Edhub\CMS\Containers\Document\Application\Actions\GetDocumentsList\GetDocumentsList;
use Edhub\CMS\Containers\Document\Application\Actions\UpdateDocuments\UpdateDocumentAction;
use Edhub\CMS\Containers\Document\Application\Actions\UpdateDocuments\UpdateDocumentTransporter;
use Edhub\CMS\Containers\Document\UI\Requests\CreateDocumentRequest;
use Edhub\CMS\Containers\Document\UI\Requests\UpdateDocumentRequest;
use Edhub\CMS\Containers\Document\UI\Transformers\DocumentTransformer;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface as Logger;

class DocumentController extends BaseApiController
{

    public function store(CreateDocumentRequest $request, CreateDocumentAction $action, Logger $logger)
    {
        $this->prepareRoles($request);

        try {
            DB::beginTransaction();
            $document = $action->run(new CreateDocumentTransporter($request->all()));
            DB::commit();

            $logger->info('Document was saved', ['type' => 'cms.documents.store.success']);

            return $this->success([
                'document' => $this->transform($document, DocumentTransformer::class)
            ]);
        } catch (\Throwable $exception) {
            $logger->error($exception->getMessage(), ['type' => 'cms.documents.store.failed']);
            throw $exception;
        }
    }

    public function update(int $id, UpdateDocumentAction $action, UpdateDocumentRequest $request, Logger $logger)
    {

        $this->prepareRoles($request);

        try {
            $request->merge(compact('id'));

            DB::beginTransaction();
            $document = $action->run(new UpdateDocumentTransporter($request->all()));
            DB::commit();

            $logger->info('Document was updated', ['type' => 'cms.documents.update.success']);

            return $this->success([
                'document' => $this->transform($document, DocumentTransformer::class)
            ]);
        } catch (\Throwable $exception) {
            $logger->error($exception->getMessage(), ['type' => 'cms.documents.update.failed']);
            throw $exception;
        }
    }

    public function list(GetDocumentsList $action, Logger $logger)
    {
        try {
            $document = $action->run();

            return $this->success([
                'documents' => $this->transform($document, DocumentTransformer::class)
            ]);
        } catch (\Throwable $exception) {
            $logger->error($exception->getMessage(), ['type' => 'cms.documents.list.failed']);
            throw $exception;
        }
    }

    public function show(int $id, GetDocumentsAction $action, Logger $logger)
    {
        try {
            $document = $action->run(new GetDocumentsTransporter(['id' => $id]));

            return $this->success([
                'document' => $this->transform($document, DocumentTransformer::class)
            ]);
        } catch (\Throwable $exception) {
            $logger->error($exception->getMessage(), ['type' => 'cms.documents.show.failed']);
            throw $exception;
        }
    }

    public function remove(int $id, DeleteDocumentsAction $action, Logger $logger)
    {
        try {
            DB::beginTransaction();
            $action->run(new DeleteDocumentTransporter(['id' => $id]));
            DB::commit();
            $logger->info("Document {$id} is removed.", ['type' => 'cms.documents.remove.success']);
            return $this->success();
        } catch (\Throwable $exception) {
            $logger->error($exception->getMessage(), ['type' => 'cms.documents.remove.failed']);
            throw $exception;
        }
    }

    private function prepareRoles(Request $request): void
    {
        $splitRoles = explode(',', $request->get('roles', ''));
        $request->merge(['roles' => $splitRoles]);
    }
}