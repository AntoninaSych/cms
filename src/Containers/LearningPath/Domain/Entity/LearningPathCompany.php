<?php

namespace Edhub\CMS\Containers\LearningPath\Domain\Entity;

use Edhub\CMS\Containers\Course\Domain\Values\CompanyId;
use Edhub\CMS\Containers\LearningPath\Domain\Values\LearningPathId;
use Illuminate\Database\Eloquent\Model;

class LearningPathCompany extends Model
{
    protected $table = 'learning_path_companies';
    public $timestamps = false;

    public function company(): CompanyId
    {
        return CompanyId::new($this->company_id);
    }

    public static function create(LearningPathId $learningPathId, CompanyId $companyId): LearningPathCompany
    {
        $learningPathCompany = new self();
        $learningPathCompany->setAttribute('learning_path_id', $learningPathId->value());
        $learningPathCompany->setAttribute('company_id', $companyId->value());

        return $learningPathCompany;
    }
}