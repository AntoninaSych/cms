<?php


namespace Edhub\CMS\Containers\Document\Domain\Values;


final class DocumentId
{
    private $rawId;


    private function __construct(int $rawId)
    {
        $this->rawId = $rawId;
    }

    public static function new(int $rawId): DocumentId
    {
        return new self($rawId);
    }

    public function value(): int
    {
        return $this->rawId;
    }

    public function isEqualTo(DocumentId $roleId): bool
    {
        return $this->value() === $roleId->value();
    }
}