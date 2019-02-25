<?php

namespace Edhub\CMS\Containers\Document\Domain\Entities;

//@TODO Fix this using @Shared
use App\Containers\Media\Services\MediaEntityTrait;
use App\Containers\Media\Services\TypeFile;

use Edhub\CMS\Containers\Document\Domain\Collections\RoleIdCollection;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentDescription;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentId;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentTitle;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentType;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\LearningPath;
use Edhub\CMS\Containers\LearningPath\Domain\Values\LearningPathId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia\{HasMedia, HasMediaTrait};

class Document extends Model implements HasMedia
{
    use HasMediaTrait, MediaEntityTrait;
    private const DOCUMENT_COLLECTION = 'documents';
    use SoftDeletes;

    protected $table = 'content_documents';
    protected $softDelete = true;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public static function create(DocumentTitle $title, DocumentType $type)
    {
        $document = new self();
        $document->changeTitle($title);
        $document->changeType($type);

        return $document;
    }

    public function changeId(DocumentId $id): void
    {
        $this->setAttribute('id', $id->value());
    }

    public function changeTitle(DocumentTitle $title): void
    {
        $this->setAttribute('title', $title->value());
    }

    public function changeDescription(DocumentDescription $description): void
    {
        $this->setAttribute('description', $description->value());
    }

    public function changeType(DocumentType $type): void
    {
        $this->setAttribute('type', $type->value());
    }

    public function id(): DocumentId
    {
        return DocumentId::new((string)$this->getAttribute('id'));
    }

    public function title(): DocumentTitle
    {
        return DocumentTitle::new((string)$this->getAttribute('title'));
    }

    public function description(): DocumentDescription
    {
        return DocumentDescription::new((string)$this->getAttribute('description'));
    }

    public function type(): DocumentType
    {
        return DocumentType::new((int)$this->getAttribute('type'));
    }

    public function syncRoles(RoleIdCollection $roleIds): void
    {
        $this->document_role_relation()->delete();
        $documentRoles = [];
        foreach ($roleIds as $role) {

            $documentRoles[] = DocumentRole::create($this->id(), $role);
        }
        $this->document_role_relation()->saveMany($documentRoles);
        $this->load('document_role_relation');
    }


    public function roles(): RoleIdCollection
    {
        $docRolesList = $this->document_role_relation->all();
        $roleIdCollection = RoleIdCollection::make();
        foreach ($docRolesList as $documentRole) {

            $roleIdCollection->add($documentRole->role());
        }

        return $roleIdCollection;
    }


    /**
     * @deprecated
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function document_role_relation()
    {
        return $this->hasMany(DocumentRole::class, 'content_document_id', 'id');
    }

    public function updateLearningPathRelation(LearningPathId $learningPathId): void
    {
        $learningPathType = DocumentType::learningPath();
        if ($this->type()->isEqual($learningPathType)) {
            $this->learningPathRelation()->sync([$learningPathId->value()]);
        }
    }

    public function learningPathId(): ?LearningPathId
    {
        $learningPath = $this->learningPathRelation()->first();

        if ($learningPath) {
            return $learningPath->id();
        }

        return null;
    }


    /**
     * @deprecated
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function learningPathRelation()
    {
        return $this->belongsToMany(LearningPath::class, 'learning_path_documents',
            'content_document_id', 'learning_path_id');
    }


    public function replaceFile(UploadedFile $file): void
    {
        $this->removeFile();

        $this->addMedia($file)
            ->usingFileName($file->getClientOriginalName())
            ->toMediaCollection(self::DOCUMENT_COLLECTION);
    }

    public function link(): string
    {
        $media = $this->getFirstMedia(self::DOCUMENT_COLLECTION);
        $typeDocument = TypeFile::file();
        $url = (!empty($media))
            ? $this->getFakeUrl($media->getFullUrl(), $typeDocument)
            : '';

        return $url;
    }

    public function extension(): string
    {
        $media = $this->getFirstMedia(self::DOCUMENT_COLLECTION);

        return $media->getExtensionAttribute();
    }

    private function removeFile(): void
    {
        $this->clearMediaCollection(self::DOCUMENT_COLLECTION);
    }
}