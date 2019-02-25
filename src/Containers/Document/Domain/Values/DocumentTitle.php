<?php


namespace Edhub\CMS\Containers\Document\Domain\Values;


use Edhub\Shared\Exceptions\InvalidArgumentException;


final class DocumentTitle
{
    private $rawTitle;

    const MAX_LENGTH = 90;

    private function __construct(string $rawTitle)
    {
        $this->validateTitle($rawTitle);
        $this->rawTitle = $rawTitle;
    }

    public static function new(string $rawTitle): DocumentTitle
    {
        return new self($rawTitle);
    }

    public function value(): string
    {
        return $this->rawTitle;
    }

    public function __toString(): string
    {
        return (string)$this->rawTitle;
    }

    public function validateTitle(string $rawTitle): void
    {
        if (empty(trim($rawTitle))) {
            throw new InvalidArgumentException('Document title is empty.');
        }
        if (strlen($rawTitle) > self::MAX_LENGTH ) {
            throw new InvalidArgumentException('Max size of the document title is '. self::MAX_LENGTH );
        }
    }
}

