<?php

namespace Edhub\CMS\Containers\Course\Domain\Values;


use Spatie\MediaLibrary\Models\Media;

final class Image
{
    /** @var Media */
    private $media;

    private function __construct() {}

    public static function new(?Media $media)
    {
        $image = new self;
        $image->media = $media;

        return $image;
    }

    public function sizes(): array
    {
        $collection = $this->media->getGeneratedConversions();

        return $collection->all();
    }

    public function fullUrl(): string
    {
        return $this->media->getFullUrl();
    }
}