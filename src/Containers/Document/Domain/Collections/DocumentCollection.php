<?php


namespace Edhub\CMS\Containers\Document\Domain\Collections;


use Edhub\Shared\Assertions\Assertion;
use Doctrine\Common\Collections\ArrayCollection;
use Edhub\CMS\Containers\Document\Domain\Entities\Document;

class DocumentCollection extends ArrayCollection
{
    public function __construct(array $elements = [])
    {
        Assertion::allIsInstanceOf($elements, Document::class);
        parent::__construct($elements);
    }

    public static function make(array $documents = []): DocumentCollection
    {
        return new static($documents);
    }
}