<?php

namespace Edhub\CMS\Containers\CourseCategory\Domain;

use Edhub\Shared\Assertions\Assertion;
use Edhub\CMS\Containers\Common\Values\Language;

final class CourseCategoryTitle
{
    /** @var string */
    private $title;
    /** @var Language */
    private $language;

    public function __construct(string $title, Language $language)
    {
        Assertion::notEmpty($title, 'Course category title is empty.');
        $this->title = $title;
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return Language
     */
    public function language(): Language
    {
        return $this->language;
    }
}