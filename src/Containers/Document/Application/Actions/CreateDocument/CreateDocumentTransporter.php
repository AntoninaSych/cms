<?php

namespace Edhub\CMS\Containers\Document\Application\Actions\CreateDocument;

/**
 * @property-read string|null $title
 * @property-read string|null $description
 * @property-read int|null $learning_path_id
 * @property-read int|null $type
 * @property-read int[] $roles
 * @property-read \Illuminate\Http\UploadedFile|null $file
 */
class CreateDocumentTransporter
{
    private $title = null;
    private $description = null;
    private $learning_path_id = null;
    private $type = null;
    private $roles = [];
    private $file = null;

//    protected $schema = [
//        'type' => 'object',
//        'properties' => [
//            'title' => ['type' => 'string'],
//            'description' => ['type' => 'string'],
//            'learning_path_id' => ['type' => 'integer'],
//            'type' => ['type' => 'integer'],
//            'file' => ['type' => 'string'],
//            'roles' => [
//                'type' => 'array',
//                'items' => ['type' => 'integer']
//            ]
//        ],
//        'default' => [
//            'description' => '',
//            'learning_path_id' => null
//        ]
//    ];

    public function __construct(array $input = [])
    {
        $this->title = $input['title'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->learning_path_id = $input['learning_path_id'] ?? null;
        $this->type = $input['type'] ?? null;
        $this->roles= $input['roles'] ?? null;
        $this->file = $input['file'] ?? null;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        throw new \RuntimeException("Property $name is not exist.");
    }
}