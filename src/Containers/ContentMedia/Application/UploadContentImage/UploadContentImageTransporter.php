<?php

namespace Edhub\CMS\Containers\ContentMedia\Appliaction\UploadContentImage;


use Edhub\Shared\Exceptions\NotFound;

class UploadContentImageTransporter
{
    protected $image = null;


    public function __construct($media)
    {
        if ($media) {
            $this->image = $media;
        }
    }

    public function __get($name)
    {
        if ($name === 'image') {
            return $this->image;
        }
        throw new NotFound('Property not defined');
    }

}