<?php

namespace Edhub\CMS\Containers\Chapter\UI\Requests;

use Edhub\CMS\Containers\Chapter\Application\Actions\UpdateChapter\UpdateChapterTransporter;

class UpdateChapterRequest extends CreateChapterRequest
{
    protected $transporter = UpdateChapterTransporter::class;

    public function rules(): array
    {
        return parent::rules();
    }
}