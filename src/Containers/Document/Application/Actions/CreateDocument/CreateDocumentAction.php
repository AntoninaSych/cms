<?php


namespace Edhub\CMS\Containers\Document\Application\Actions\CreateDocument;


use Edhub\Shared\Exceptions\InvalidArgumentException;
use Edhub\CMS\Containers\Document\Domain\Collections\RoleIdCollection;
use Edhub\CMS\Containers\Document\Domain\Entities\Document;
use Edhub\CMS\Containers\Document\Domain\Repositories\DocumentRepository;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentDescription;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentTitle;
use Edhub\CMS\Containers\Document\Domain\Values\DocumentType;
use Edhub\CMS\Containers\Document\Domain\Values\RoleId;
use Edhub\CMS\Containers\LearningPath\Domain\Values\LearningPathId;


class CreateDocumentAction
{

    /**
     * @var DocumentRepository
     */
    protected $documents;

    public function __construct(DocumentRepository $documents)
    {
        $this->documents = $documents;
    }

    public function run(CreateDocumentTransporter $transporter): Document
    {
        [$titleRaw, $descriptionRaw, $typeRaw, $learningPathIdRaw, $file] = [
            $transporter->title, $transporter->description, $transporter->type, $transporter->learning_path_id, $transporter->file
        ];
        $type = DocumentType::new($typeRaw);
        $learningPathType = DocumentType::learningPath();
        $isLearningPath = $type->isEqual($learningPathType);

        if ($isLearningPath && is_null($learningPathIdRaw)) {
            throw new InvalidArgumentException('Learning path ID is required because due to document type.');
        }

        $title = DocumentTitle::new($titleRaw);
        $description = DocumentDescription::new($descriptionRaw);
        $document = Document::create($title, $type);
        $document->changeDescription($description);

        $this->documents->save($document);

        if ($isLearningPath) {
            $this->updateLearningPath($transporter, $document);
        }

        if (!is_null($file)) {
            $document->replaceFile($file);
        }

        $this->updateRoles($transporter, $document);

        return $document;
    }

    private function updateLearningPath(CreateDocumentTransporter $transporter, Document $document): void
    {
        $learningPathIdRaw = $transporter->learning_path_id;
        if (!is_null($learningPathIdRaw)) {
            $learningPathId = LearningPathId::new($learningPathIdRaw);
            $document->updateLearningPathRelation($learningPathId);
        }
    }

    private function updateRoles(CreateDocumentTransporter $transporter, Document $document): void
    {
        $idRoles = $transporter->roles;

        if (!is_null($idRoles)) {
            $roleIdCollection = RoleIdCollection::make();

             foreach ($idRoles as $idRole) {
                $roleItem = RoleId::new((int)$idRole);

                 $roleIdCollection->add($roleItem);
            }

            $document->syncRoles($roleIdCollection);
        }
    }

}