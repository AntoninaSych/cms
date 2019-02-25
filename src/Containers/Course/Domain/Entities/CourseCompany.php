<?php

namespace Edhub\CMS\Containers\Course\Domain\Entities;

use Edhub\CMS\Containers\Course\Domain\Values\CompanyId;
use Edhub\CMS\Containers\Course\Domain\Values\CourseId;
use Illuminate\Database\Eloquent\Model;

class CourseCompany extends Model
{
    protected $table = 'courses_companies';
    public $timestamps = false;

    public static function create(CourseId $courseId, CompanyId $companyId, bool $isPublic): CourseCompany
    {
        $courseCompany = new self;
        $courseCompany->setAttribute('course_id', $courseId->value());
        $courseCompany->setAttribute('company_id', $companyId->value());
        $courseCompany->setAttribute('is_public', $isPublic);

        return $courseCompany;
    }

    public function company(): CompanyId
    {
        return CompanyId::new(
            (int)$this->getAttribute('company_id')
        );
    }

    public function isPublic(): bool
    {
        return (bool)$this->getAttribute('is_public');
    }
}