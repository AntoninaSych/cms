<?php


namespace Edhub\CMS\Containers\Document\Domain\Values;


final class DocumentDescription
{
    private $rawDescription;


    private function __construct(string $rawDescription)
    {
        $this->rawDescription = $rawDescription;
    }

    public static function new(string $rawDescription): DocumentDescription
    {
        return new self($rawDescription);
    }

    public function value(): string
    {
        return $this->rawDescription;
    }

    public function __toString(): string
    {
        return (string)$this->rawDescription;
    }


}