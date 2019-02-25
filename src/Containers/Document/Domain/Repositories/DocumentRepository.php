<?php


namespace Edhub\CMS\Containers\Document\Domain\Repositories;


use Edhub\CMS\Containers\Document\Domain\Collections\DocumentCollection;
use Edhub\CMS\Containers\Document\Domain\Entities\Document;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentId;

interface DocumentRepository
{
    public function save(Document $document): void;

    public function getOne(DocumentId $id): Document;

    public function list(): DocumentCollection;

    public function remove(Document $id): void;
}