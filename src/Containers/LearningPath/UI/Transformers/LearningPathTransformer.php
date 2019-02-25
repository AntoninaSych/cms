<?php

namespace Edhub\CMS\Containers\LearningPath\UI\Transformers;

use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Edhub\CMS\Containers\Course\Domain\Values\CompanyId;
use Edhub\CMS\Containers\Document\UI\Transformers\DocumentTransformer;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class LearningPathTransformer extends BaseTransformer
{
    protected $availableIncludes = ['documents'];

    public function transform(LearningPath $learningPath): array
    {
        return [
            'id' => $learningPath->id()->value(),
            'title' => $learningPath->title()->value(),
            'subtitle' => $learningPath->subtitle()->value(),
            'description' => $learningPath->description()->value(),
            'status' => $learningPath->status()->value(),
            'points' => $learningPath->points()->value(),
            'language' => $learningPath->language()->value(),
            'code' => $learningPath->code()->value(),
            'meta' => $learningPath->meta()->value(),
            'image' => $learningPath->images(),

            'courses' => array_map(function (Course $course) {
                return $course->id()->value();
            }, $learningPath->courses()),
            'companies' => array_map(function (CompanyId $companyId){
                return $companyId->value();
            },  $learningPath->companies()),
            'updated_at' => $learningPath->wasUpdatedAt()->getTimestamp()
        ];
    }

    public function includeDocuments(LearningPath $learningPath)
    {
    return $this->collection($learningPath->documents(), new DocumentTransformer());
    }
}

