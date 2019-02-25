<?php

namespace Edhub\CMS\Containers\ContentMedia\Appliaction\UploadContentImage;

use Edhub\CMS\Containers\ContentMedia\Domain\Entities\ContentImage;

class UploadContentImageAction
{

    public $imageUploader;

    public function __construct(ContentImage $contentImage)
    {
        $this->imageUploader = $contentImage;
    }

    public function run(UploadContentImageTransporter $transporter): string
    {

        $image = $transporter->image;

        if (!is_null($image)) {

            $this->imageUploader->uploadImage($transporter);
        }

        return $this->imageUploader->image();
    }

}