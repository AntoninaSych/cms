<?php

namespace Edhub\CMS;

use Edhub\CMS\Containers\Course\UI\Transformers\CourseTransformer;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class CmsLearningPathTransformer extends BaseTransformer
{
    protected $availableIncludes = ['courses', 'documents'];

    public function transform(LearningPath $learningPath): array
    {
        return [
            'id' => $learningPath->id()->value(),
            'title' => $learningPath->title()->value(),
            'subtitle' => $learningPath->subtitle()->value(),
            'image' => $learningPath->images(),
            'description' => $learningPath->description()->value(),
            'status' => $learningPath->status()->value(),
            'points' => $learningPath->points()->value(),
            'language' => $learningPath->language()->value(),
            'meta' => $learningPath->meta()->value(),
        ];
    }

    public function includeCourses(LearningPath $learningPath)
    {
        return $this->collection($learningPath->courses(), new CourseTransformer());
    }

    public function includeDocuments(LearningPath $learningPath)
    {
        return $this->collection($learningPath->documents(), new CmsLearningPathDocumentTransformer());
    }
}