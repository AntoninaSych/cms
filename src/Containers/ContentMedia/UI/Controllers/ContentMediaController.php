<?php

namespace Edhub\CMS\Containers\ContentMedia\UI\Controllers;

use Edhub\CMS\Containers\ContentMedia\Appliaction\UploadContentFile\UploadContentFileAction;
use Edhub\CMS\Containers\ContentMedia\Appliaction\UploadContentFile\UploadContentFileTransporter;
use Edhub\CMS\Containers\ContentMedia\Appliaction\UploadContentImage\UploadContentImageAction;
use Edhub\CMS\Containers\ContentMedia\Appliaction\UploadContentImage\UploadContentImageTransporter;
use Edhub\CMS\Containers\ContentMedia\UI\Requests\RequestFile;
use Edhub\CMS\Containers\ContentMedia\UI\Requests\RequestImage;
use Edhub\Shared\UI\BaseApiController;

class ContentMediaController extends BaseApiController
{
    public function uploadFile(RequestFile $request, UploadContentFileAction $action)
    {
        $file = $request->file('media');
        $transporter = new UploadContentFileTransporter($file);
        $url = $action->run($transporter);

        return $this->success([
            'link' => $url
        ]);
    }

    public function uploadImage(RequestImage $request, UploadContentImageAction $action)
    {
        $file = $request->file('media');
        $transporter = new UploadContentImageTransporter($file);
        $url = $action->run($transporter);


        return $this->success([
            'link' => $url
        ]);
    }
}