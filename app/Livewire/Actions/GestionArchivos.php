<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GestionArchivos extends Component
{
    use WithFileUploads;

    public $model;
    public $archivo;

    public function mount($model)
    {
        $this->model = $model;
    }

    public function guardarArchivo()
    {
        $this->validate([
            'archivo' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:10240',
        ]);

        $nombreOriginal = $this->archivo->getClientOriginalName();

        $nombreSinExtension = pathinfo($nombreOriginal, PATHINFO_FILENAME);

        $extension = strtolower($this->archivo->getClientOriginalExtension());

        $nombreLimpio = str($nombreSinExtension)
            ->ascii()
            ->replaceMatches('/[^A-Za-z0-9_\-]/', '_')
            ->toString();

        $esRaw = in_array($extension, ['doc', 'docx', 'xls', 'xlsx']);

        $nombreFinal = $esRaw
            ? $nombreLimpio . '.' . $extension
            : $nombreLimpio;

        // 📦 LÍMITE TOTAL POR CLIENTE (300 MB)

        $totalBytes = $this->model
            ->getMedia('archivos')
            ->sum('size');

        $nuevoArchivo = $this->archivo->getSize();

        $limiteCliente = 300 * 1024 * 1024;

        if (($totalBytes + $nuevoArchivo) > $limiteCliente) {

            session()->flash(
                'error',
                '⚠️ Este cliente alcanzó el límite de 300 MB en documentos.'
            );

            return;
        }

        $this->model->addMedia($this->archivo->getRealPath())
            ->usingName($nombreOriginal)
            ->usingFileName($nombreFinal)
            ->toMediaCollection('archivos', 'cloudinary');

        $this->archivo = null;

$this->dispatch('$refresh');

session()->flash('success', '✅ ¡Archivo guardado con éxito!');
    }

    public function eliminarArchivo($id)
    {
        $media = Media::find($id);

        if ($media) {
    $media->delete();

    $this->dispatch('$refresh');

    session()->flash(
        'success',
        '🗑️ Archivo eliminado correctamente.'
    );
}
    }

    public function render()
    {
        return view('livewire.actions.gestion-archivos', [
            'archivos' => $this->model->getMedia('archivos')
        ]);
    }
}