<?php


namespace Edhub\CMS\Containers\Document\Domain\Values;


class RoleId
{
    private $rawId;


    private function __construct(int $rawId)
    {
        $this->rawId = $rawId;
    }

    public static function new(int $rawId): RoleId
    {
        return new self($rawId);
    }

    public function value(): int
    {
        return $this->rawId;
    }

    public function isEqualTo(RoleId $roleId): bool
    {
        return $this->value() === $roleId->value();
    }
}