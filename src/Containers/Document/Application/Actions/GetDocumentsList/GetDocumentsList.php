<?php


namespace Edhub\CMS\Containers\Document\Application\Actions\GetDocumentsList;


use Edhub\CMS\Containers\Document\Domain\Collections\DocumentCollection;
use Edhub\CMS\Containers\Document\Domain\Repositories\DocumentRepository;

class GetDocumentsList
{

    /**
     * @var DocumentRepository
     */
    protected $documents;

    public function __construct(DocumentRepository $documents)
    {
        $this->documents = $documents;
    }

    public function run(): DocumentCollection
    {
        return $this->documents->list();
    }
}