<?php

namespace Edhub\CMS\Containers\CourseCategory\Domain;

use Edhub\Shared\Assertions\Assertion;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CourseCategory extends Model
{
    use HasTranslations;
    
    protected $table = 'course_categories';
    public $translatable = ['title'];
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    public function id(): int
    {
        return (int)$this->getAttribute('id');
    }

    /** @return array [lang => title] */
    public function title(): array
    {
        $rawTranslations = $this->getTranslations('title');

        return $rawTranslations;
    }

    public function changeTitle(CourseCategoryTitle ...$titles): void
    {
        Assertion::notEmpty($titles, 'Course category must have title.');

        foreach ($titles as $title) {
            $this->setTranslation('title', $title->language()->value(), $title->title());
        }
    }
}
