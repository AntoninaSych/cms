<?php

namespace Edhub\CMS\Containers\Course\UI\Requests;

use Edhub\Shared\UI\Requests\Request;
use Edhub\CMS\Containers\Course\Application\Actions\UpdateCourse\UpdateCourseTransporter;

class UpdateCourseRequest extends Request
{
    protected $transporter = UpdateCourseTransporter::class;

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'subtitle' => ['sometimes', 'string'],
            'description' => ['sometimes', 'array'],
            'status' => ['required', 'int'],
            'language' => ['required', 'string'],
            'companies' => ['required', 'array'],
            'companies.*.company' => ['required', 'sometimes', 'int'],
            'companies.*.isPublic' => ['required', 'sometimes', 'boolean'],
            'categories' => ['array'],
            'categories.*' => ['int'],
            'chapters' => ['array'],
            'image' => ['sometimes', 'string', 'nullable']
        ];
    }

}