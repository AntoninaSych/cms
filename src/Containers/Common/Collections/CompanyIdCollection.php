<?php

namespace Edhub\CMS\Containers\Common\Collections;

use Edhub\Shared\Assertions\Assertion;
use Doctrine\Common\Collections\ArrayCollection;
use Edhub\CMS\Containers\Course\Domain\Values\CompanyId;

class CompanyIdCollection extends ArrayCollection
{
    public function __construct(array $companiesIds = [])
    {
        Assertion::allIsInstanceOf($companiesIds, CompanyId::class, 'Element must implement CompanyId type.');
        parent::__construct($companiesIds);
    }

    public static function make(array $companiesIds = []): CompanyIdCollection
    {
        return new static($companiesIds);
    }

    public function toScalarList(): array
    {
        return array_map(function (CompanyId $companyId) {
            return $companyId->value();
        }, $this->getValues());
    }
}