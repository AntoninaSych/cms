<?php

namespace Edhub\CMS\Containers\Course\Domain\Collections;

use Edhub\Shared\Assertions\Assertion;
use Doctrine\Common\Collections\ArrayCollection;
use Edhub\CMS\Containers\Course\Domain\Entities\CourseCompany;
use Edhub\CMS\Containers\Course\Domain\Values\CompanyId;

class CourseCompanies extends ArrayCollection
{
    public function __construct(array $items = [])
    {
        Assertion::allIsInstanceOf($items, CourseCompany::class);

        parent::__construct($items);
    }

    public function hasAccessToCourse(CompanyId $companyId): bool
    {
        return $this->exists(function ($key, CourseCompany $courseCompany) use ($companyId) {
            return $courseCompany->company()->isEqualTo($companyId);
        });
    }

    public function hasPublicAccessToCourse(CompanyId $companyId): bool
    {
        /** @var CourseCompany $courseCompany */
        $courseCompany = $this->filter(function (CourseCompany $courseCompany) use ($companyId) {
            return $courseCompany->company()->isEqualTo($companyId);
        })->first();
        if (empty($courseCompany)) {
            return false;
        }

        return $courseCompany->isPublic();
    }
}