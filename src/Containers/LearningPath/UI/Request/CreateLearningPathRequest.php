<?php

namespace Edhub\CMS\Containers\LearningPath\UI\Request;

use Edhub\CMS\Containers\LearningPath\Application\Actions\CreateLearningPath\CreateLearningPathTransporter;
use Edhub\Shared\UI\Requests\Request;


class CreateLearningPathRequest extends Request
{
    protected $transporter = CreateLearningPathTransporter::class;

    public function rules(): array
    {

        return [
            'title' => ['required', 'string'],
            'subtitle' => ['sometimes','string'],
            'status' => ['required','integer'],
            'description' => ['sometimes','array'],
            'meta' => ['array'],
            'language' => ['string'],
            'courses.*' => ['sometimes', 'int'],
            'companies.*' => ['sometimes', 'int']
        ];
    }
}
