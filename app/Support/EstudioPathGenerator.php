<?php

namespace App\Support;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Cliente;

class EstudioPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $slug = 'general';

        // SI EL MODELO ES CLIENTE
        if ($media->model instanceof Cliente) {

            $cliente = $media->model;

            // BUSCAR ABOGADO DUEÑO
            $abogado = $cliente->abogado;

            if ($abogado && $abogado->slug_estudio) {
                $slug = $abogado->slug_estudio;
            }
        }

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
