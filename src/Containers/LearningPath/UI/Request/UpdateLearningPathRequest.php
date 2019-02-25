<?php
namespace Edhub\CMS\Containers\LearningPath\UI\Request;

use Edhub\Shared\UI\Requests\Request;
use Edhub\CMS\Containers\LearningPath\Application\Actions\GetLearningPathList\UpdateLearningPathTransporter;

class UpdateLearningPathRequest  extends Request
{
    protected $transporter = UpdateLearningPathTransporter::class;

    public function rules(): array
    {
        return [
            'title' => ['required','string'],
            'subtitle' => ['sometimes','string'],
            'status' => ['required','integer'],
            'description' => ['sometimes','array'],
            'meta' => ['array'],
            'language' => [ 'string'],
            'courses.*' => ['sometimes', 'int'],
            'companies.*' => ['sometimes', 'int']
        ];
    }
}
