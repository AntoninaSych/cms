<?php

namespace Edhub\CMS\Containers\ContentMedia\Appliaction\UploadContentFile;

use Edhub\CMS\Containers\ContentMedia\Domain\Entities\ContentFile;

class UploadContentFileAction
{
    protected $fileUploader;

    public function __construct(ContentFile $file)
    {
        $this->fileUploader = $file;
    }

    public function run(UploadContentFileTransporter $transporter)
    {

        $file = $transporter->file;

        if (!is_null($file)) {

            $this->fileUploader->uploadFile($transporter);
        }

        return $this->fileUploader->file();
    }
}