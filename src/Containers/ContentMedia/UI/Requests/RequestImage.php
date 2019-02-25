<?php

namespace Edhub\CMS\Containers\ContentMedia\UI\Requests;


use Edhub\Shared\UI\Requests\Request;

class RequestImage extends Request
{
    public function rules(): array
    {

        return [
            'media' => ['required', 'image'],
        ];
    }

}