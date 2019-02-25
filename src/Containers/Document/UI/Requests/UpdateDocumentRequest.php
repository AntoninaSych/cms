<?php

namespace Edhub\CMS\Containers\Document\UI\Requests;

use Edhub\Shared\UI\Requests\Request;
use Edhub\CMS\Containers\Document\Application\Actions\UpdateDocuments\UpdateDocumentTransporter;

class UpdateDocumentRequest extends Request
{
    protected $transporter = UpdateDocumentTransporter::class;

    public function rules(): array
    {
        // Max file size is 2 gb
        $maxFileSizeInKilobytes = 1000 * 2000;

        return [
            'title' => ['required', 'string','max:90'],
            'description' => ['sometimes','string','max:500'],
            'type' => ['required', 'integer'],
            'file' => ['sometimes', "max:$maxFileSizeInKilobytes"],
        ];
    }
}
