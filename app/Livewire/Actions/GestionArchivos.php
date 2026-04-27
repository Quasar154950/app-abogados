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

        $nombreLimpio = str($nombreSinExtension)
            ->ascii()
            ->replaceMatches('/[^A-Za-z0-9_\-]/', '_')
            ->toString();

        $this->model->addMedia($this->archivo->getRealPath())
            ->usingName($nombreOriginal)
            ->usingFileName($nombreLimpio)
            ->toMediaCollection('archivos');

        $this->archivo = null;

        session()->flash('success', '✅ ¡Archivo guardado con éxito!');
    }

    public function eliminarArchivo($id)
    {
        $media = Media::find($id);

        if ($media) {
            $media->delete();
            session()->flash('success', '🗑️ Archivo eliminado correctamente.');
        }
    }

    public function render()
    {
        return view('livewire.actions.gestion-archivos', [
            'archivos' => $this->model->getMedia('archivos')
        ]);
    }
}