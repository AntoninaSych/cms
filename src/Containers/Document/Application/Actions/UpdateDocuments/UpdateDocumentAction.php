<?php


namespace Edhub\CMS\Containers\Document\Application\Actions\UpdateDocuments;


use Edhub\CMS\Containers\Document\Domain\Collections\RoleIdCollection;
use Edhub\CMS\Containers\Document\Domain\Entities\Document;
use Edhub\CMS\Containers\Document\Domain\Exceptions\LearningPathIdRequired;
use Edhub\CMS\Containers\Document\Domain\Repositories\DocumentRepository;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentDescription;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentId;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentTitle;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentType;
use Edhub\CMS\Containers\Document\Domain\Values\RoleId;
use Edhub\CMS\Containers\LearningPath\Domain\Values\LearningPathId;

class UpdateDocumentAction
{

    /**
     * @var DocumentRepository
     */
    protected $documents;

    public function __construct(DocumentRepository $documents)
    {
        $this->documents = $documents;
    }


    public function run(UpdateDocumentTransporter $transporter): Document
    {
        [$id, $titleRow, $descriptionRaw, $typeRaw, $learningPathIdRaw, $file] = [
            $transporter->id, $transporter->title, $transporter->description, $transporter->type,$transporter->learning_path_id, $transporter->file
        ];

        $id = DocumentId::new((int)$id);
        $document = $this->documents->getOne($id);
        $learningPathType = DocumentType::learningPath();
        $type = DocumentType::new($typeRaw);
        $isLearningPath = $type->isEqual($learningPathType);

        if ($isLearningPath && is_null($learningPathIdRaw)) {
            throw new LearningPathIdRequired('Learning path ID is required due to document type.');
        }
        if ($document->type()->isEqual($learningPathType) && is_null($learningPathIdRaw)) {
            throw new LearningPathIdRequired('Learning path ID is required, because of type: ' . $document->type()->value());
        }
        if (!is_null($typeRaw)) {
            $type = DocumentType::new($typeRaw);
            $document->changeType($type);
        }
        if (!is_null($titleRow)) {
            $title = DocumentTitle::new($titleRow);
            $document->changeTitle($title);
        }
        if (!is_null($descriptionRaw)) {
            $description = DocumentDescription::new($descriptionRaw);
            $document->changeDescription($description);
        }

        $this->documents->save($document);

        if (!is_null($file)) {
            $document->replaceFile($file);
        }

        if ($isLearningPath) {
            $this->updateLearningPath($transporter, $document);
        }
        $this->updateRoles($document, $transporter);


        return $document;
    }

    private function updateLearningPath(UpdateDocumentTransporter $transporter, Document $document): void
    {
        $learningPathIdRaw = $transporter->learning_path_id;
        if (!is_null($learningPathIdRaw)) {
            $learningPathId = LearningPathId::new($learningPathIdRaw);
            $document->updateLearningPathRelation($learningPathId);
        }
    }

    private function updateRoles(Document $document, UpdateDocumentTransporter $transporter): void
    {
        $idRoles = $transporter->roles;

        if (!is_null($idRoles)) {
            $roleIdCollection = RoleIdCollection::make();
            $idRoles = $transporter->roles;
            foreach ($idRoles as $idRole) {
                $roleItem = RoleId::new($idRole);
                $roleIdCollection->add($roleItem);
            }
            $document->syncRoles($roleIdCollection);
        }
    }

}