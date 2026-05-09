<?php

namespace App\Support;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EstudioPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $user = auth()->user();

        $slug = $user?->slug_estudio ?? 'general';

        return "estudios/{$slug}/documentos/";
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive/';
    }
}
