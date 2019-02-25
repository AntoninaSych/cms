<?php

namespace Edhub\CMS\Containers\ContentMedia\UI\Requests;

use Edhub\Shared\UI\Requests\Request;

class RequestFile extends Request
{

    public function rules(): array
    {
        $maxFileSizeInKilobytes = 1000 * 2000;

        return [
            'media' => ['required', 'file', "max:$maxFileSizeInKilobytes"],
        ];
    }
}


