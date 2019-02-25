<?php

namespace Edhub\CMS\Containers\Course\UI\Transformers;

use Edhub\CMS\Containers\Chapter\UI\Transformers\ChapterTransformer;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategory;
use Edhub\CMS\Containers\Course\Domain\Entities\Course;
use Edhub\CMS\Containers\Course\Domain\Entities\CourseCompany;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\Shared\UI\Transformers\BaseTransformer;

class CourseTransformer extends BaseTransformer
{
    protected $availableIncludes = ['chapters'];

    public function transform(Course $course)
    {
        return [
            'id' => $course->id()->value(),
            'title' => $course->title(),
            'subtitle' => $course->subtitle(),
            'description' => $course->description(),
            'status' => $course->status()->value(),
            'language' => $course->language()->value(),
            'categories' => array_map(function (CourseCategory $category) {
                return $category->id();
            }, iterator_to_array($course->categories())),
            'companies' => array_map(function (CourseCompany $courseCompany) {
                return [
                    'company' => $courseCompany->company()->value(),
                    'isPublic' => $courseCompany->isPublic(),
                ];
            }, iterator_to_array($course->companies())),
            'paths' => array_map(function (LearningPath $learningPath) {
                return $learningPath->id()->value();
            }, $course->paths()),
            'updated_at' => $course->wasUpdatedAt()->getTimestamp(),
            'image' => $course->images()
        ];
    }

    public function includeChapters(Course $course)
    {
        return $this->collection($course->chapters(), new ChapterTransformer());
    }
}