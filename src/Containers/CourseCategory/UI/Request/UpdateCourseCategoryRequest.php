<?php

namespace Edhub\CMS\Containers\CourseCategory\UI\Request;

use Edhub\Shared\UI\Requests\Request;
use Edhub\CMS\Containers\CourseCategory\Application\Actions\UpdateCourseCategory\UpdateCourseCategoryTransporter;

class UpdateCourseCategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'title.*' => ['string'],
        ];
    }

    public function toTransporter()
    {
        $transporter = new UpdateCourseCategoryTransporter((int)$this->route('id'));
        $titles = (array)$this->get('title');
        array_map(function (string $title, string $lang) use ($transporter) {
            $transporter->add($title, $lang);
        }, $titles, array_keys($titles));

        return $transporter;
    }
}