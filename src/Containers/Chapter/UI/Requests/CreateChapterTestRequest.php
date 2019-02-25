<?php

namespace Edhub\CMS\Containers\Chapter\UI\Requests;

use Edhub\CMS\Containers\Chapter\Application\Actions\CreateChapterTest\CreateChapterTestTransporter;
use Edhub\Shared\UI\Requests\Request;

class CreateChapterTestRequest extends Request
{
    protected $transporter = CreateChapterTestTransporter::class;

    public function rules(): array
    {
        return [];
    }
}