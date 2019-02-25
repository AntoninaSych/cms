<?php

namespace Edhub\CMS\Containers\CourseCategory\Application\Actions\UpdateCourseCategory;


use Edhub\CMS\Containers\Common\Values\Language;
use Edhub\CMS\Containers\CourseCategory\Domain\CourseCategoryTitle;

class UpdateCourseCategoryTransporter
{
    /** @var CourseCategoryTitle[]  */
    public $titles = [];
    /** @var int  */
    public $id = 0;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function add(string $title, string $language): void
    {
        $this->titles[] = new CourseCategoryTitle($title, Language::new($language));
    }

//    protected $schema = [
//        'type' => 'object',
//        'properties' => [
//            'title' => [
//                'type' => 'array',
//                'items' => ['string']
//            ],
//        ],
//    ];
}