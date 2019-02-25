<?php

namespace Edhub\CMS\Containers\Chapter\UI\Requests;


use Edhub\CMS\Containers\Chapter\Application\Actions\UpdateChapterTest\UpdateChapterTestTransporter;

class UpdateChapterTestRequest extends CreateChapterTestRequest
{
    protected $transporter = UpdateChapterTestTransporter::class;
}