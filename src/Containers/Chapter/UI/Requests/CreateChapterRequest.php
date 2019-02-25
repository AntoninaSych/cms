<?php

namespace Edhub\CMS\Containers\Chapter\UI\Requests;


use Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapter\CreateChapterTransporter;
use Edhub\Shared\UI\Requests\Request;

class CreateChapterRequest extends Request
{
    protected $transporter = CreateChapterTransporter::class;

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'int'],
            'subtitle' => ['sometimes', 'max:255'],
            'content' => ['sometimes', 'array'],
            'extraLink' => ['sometimes', 'max:255'],
        ];
    }
}