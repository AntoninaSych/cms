<?php

namespace Edhub\CMS\Containers\ContentMedia\Appliaction\UploadContentFile;

use Edhub\Shared\Exceptions\NotFound;

class UploadContentFileTransporter
{
    protected $file = null;

    public function __construct($media)
    {
        if ($media) {
            $this->file = $media;
        }
    }

    public function __get($name)
    {
        if (!is_null($this->file)) {
            return $this->file;
        }
        throw new NotFound('Property not defined');
    }
}