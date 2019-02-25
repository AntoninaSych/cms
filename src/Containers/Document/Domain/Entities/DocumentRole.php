<?php


namespace Edhub\CMS\Containers\Document\Domain\Entities;


use Edhub\CMS\Containers\Document\Domain\Values\DocumentId;
use Edhub\CMS\Containers\Document\Domain\Values\RoleId;
use Illuminate\Database\Eloquent\Model;

class DocumentRole extends Model
{

    protected $table = 'content_documents_roles';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function role(): RoleId
    {
        return RoleId::new($this->role_id);
    }

    public static function create(DocumentId $documentId, RoleId $roleId): DocumentRole
    {
        $documentRole = new self();
        $documentRole->setAttribute('role_id', $roleId->value());
        $documentRole->setAttribute('content_document_id', $documentId->value());

        return $documentRole;
    }

}