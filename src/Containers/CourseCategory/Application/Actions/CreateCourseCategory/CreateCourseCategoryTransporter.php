<?php

namespace Edhub\CMS\Containers\CourseCategory\Application\Actions\CreateCourseCategory;

use Edhub\CMS\Containers\Common\Values\Language;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryTitle;

/**
 * @property-read CourseCategoryTitle[] $title
 */
class CreateCourseCategoryTransporter
{
    public $titles = [];

    public function add(string $title, string $language): void
    {
        $this->titles[] = new CourseCategoryTitle($title, Language::new($language));
    }
//    protected $schema = [
//        'type' => 'object',
//        'properties' => [
//            'title' => [
//                'type' => 'array',
//            ],
//        ],
//    ];
}