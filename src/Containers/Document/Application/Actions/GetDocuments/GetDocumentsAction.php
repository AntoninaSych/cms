<?php


namespace Edhub\CMS\Containers\Document\Application\Actions\GetDocuments;


use Edhub\CMS\Containers\Document\Domain\Entities\Document;
use Edhub\CMS\Containers\Document\Domain\Repositories\DocumentRepository;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentId;

class GetDocumentsAction
{
    /**
     * @var DocumentRepository
     */
    protected $documents;

    public function __construct(DocumentRepository $documents)
    {
        $this->documents = $documents;
    }

    public function run(GetDocumentsTransporter $transporter): Document
    {
        $id = DocumentId::new($transporter->id);

        return $this->documents->getOne($id);
    }

}