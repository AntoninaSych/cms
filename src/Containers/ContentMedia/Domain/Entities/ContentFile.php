<?php

namespace Edhub\CMS\Containers\ContentMedia\Domain\Entities;

use App\Containers\Media\Services\MediaEntityTrait;
use App\Containers\Media\Services\TypeFile;
use Edhub\CMS\Containers\ContentMedia\Appliaction\UploadContentFile\UploadContentFileTransporter;
use Edhub\CMS\Containers\ContentMedia\Domain\Values\ContentMediaType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ContentFile extends Model implements HasMedia
{
    use HasMediaTrait, MediaEntityTrait;

    const UPDATED_AT = null;

    public $table = 'content_media';
    private const FILE_COLLECTION = 'content/media/files';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = ['created_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->type = ContentMediaType::file()->value();
    }

    public function uploadFile(UploadContentFileTransporter $transporter): void
    {
        /** @var UploadedFile $file */
        $file = $transporter->file;
        $this->addMedia($file)->toMediaCollection(self::FILE_COLLECTION);
        $this->saveOrFail();
    }

    public function file(): string
    {
        $media = $this->getFirstMedia(self::FILE_COLLECTION);

        $fileType = TypeFile::file();

        $url = (!empty($media))
            ? $this->getFakeUrl($media->getFullUrl(), $fileType)
            : '';

        return $url;
    }

}