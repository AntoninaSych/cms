<?php


namespace Edhub\CMS\Containers\Document\Domain\Repositories;


use Edhub\CMS\Containers\Document\Domain\Collections\DocumentCollection;
use Edhub\CMS\Containers\Document\Domain\Entities\Document;
use Edhub\CMS\Containers\Document\Domain\Exceptions\DocumentNotFound;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentId;
use Prettus\Repository\Eloquent\BaseRepository;

class DBDocumentRepository extends BaseRepository implements DocumentRepository
{
    public function model()
    {
        return Document::class;
    }

    public function save(Document $document): void
    {
        $document->saveOrFail();
    }

    public function getOne(DocumentId $id): Document
    {
        $document = Document::query()->with('document_role_relation')->find($id->value());
        if (empty($document)) {
            throw new DocumentNotFound();
        }

        return $document;
    }

    public function list(): DocumentCollection
    {
        $documentsCollection = DocumentCollection::make();
        $documents = $this->all();
        foreach ($documents as $document) {
            $documentsCollection->add($document);
        }

        return $documentsCollection;
    }

    public function remove(Document $document): void
    {
        $document->delete();
    }
}