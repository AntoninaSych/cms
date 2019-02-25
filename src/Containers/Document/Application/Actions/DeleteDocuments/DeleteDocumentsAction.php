<?php


namespace Edhub\CMS\Containers\Document\Application\Actions\DeleteDocuments;


use Edhub\CMS\Containers\Document\Domain\Repositories\DocumentRepository;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentId;

class DeleteDocumentsAction
{

    /**
     * @var DocumentRepository
     */
    protected $documents;

    public function __construct(DocumentRepository $documents)
    {
        $this->documents = $documents;
    }

    public function run(DeleteDocumentTransporter $transporter): void
    {
        $id = DocumentId::new($transporter->id);
        $document = $this->documents->getOne($id);
        $this->documents->remove($document);
    }
}