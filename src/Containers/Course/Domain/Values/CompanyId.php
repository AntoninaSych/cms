<?php

namespace Edhub\CMS\Containers\Course\Domain\Values;

class CompanyId
{
    /**
     * @var int
     */
    private $company;

    private function __construct(int $company)
    {
        $this->company = $company;
    }

    public static function new(int $company)
    {
        return new self($company);
    }

    public function isEqualTo(CompanyId $companyId): bool
    {
        return $this->value() === $companyId->value();
    }

    public function value(): int
    {
        return $this->company;
    }
}
