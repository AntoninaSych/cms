<?php

namespace Edhub\CMS\Containers\ContentMedia\Domain\Entities;

use App\Containers\Media\Services\MediaEntityTrait;
use App\Containers\Media\Services\TypeFile;
use Edhub\CMS\Containers\ContentMedia\Appliaction\UploadContentImage\UploadContentImageTransporter;
use Edhub\CMS\Containers\ContentMedia\Domain\Values\ContentMediaType;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ContentImage extends Model implements HasMedia
{
    use HasMediaTrait, MediaEntityTrait;

    const UPDATED_AT = null;

    public $table = 'content_media';
    private const IMAGE_COLLECTION = 'content/media/images';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = ['created_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->type = ContentMediaType::image()->value();
    }

    public function uploadImage(UploadContentImageTransporter $transporter): void
    {
        $image = $transporter->image;
        $this->addMedia($image)->toMediaCollection(self::IMAGE_COLLECTION);
        $this->saveOrFail();
    }

    public function image(): string
    {
        $media = $this->getFirstMedia(self::IMAGE_COLLECTION);

        $fileType = TypeFile::image();

        $url = (!empty($media))
            ? $this->getFakeUrl($media->getFullUrl(), $fileType)
            : '';

        return $url;
    }
}
