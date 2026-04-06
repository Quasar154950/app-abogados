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
        // VALIDACIÓN MEJORADA: Ahora solo permite lo que querés
        $this->validate([
            'archivo' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:10240',
        ]);

        // Guardamos con Spatie (Mantiene tu lógica)
        $this->model->addMedia($this->archivo->getRealPath())
            ->usingFileName($this->archivo->getClientOriginalName())
            ->toMediaCollection('archivos');

        $this->archivo = null;

        session()->flash('success', '✅ ¡Archivo guardado con éxito!');
    }

    public function eliminarArchivo($id)
    {
        $media = Media::find($id);
        if ($media) {
            $media->delete(); // Spatie borra el archivo del disco automáticamente
            session()->flash('success', '🗑️ Archivo eliminado correctamente.');
        }
    }

    public function render()
    {
        // Refrescamos la colección para que se vea el cambio inmediatamente
        return view('livewire.actions.gestion-archivos', [
            'archivos' => $this->model->getMedia('archivos')
        ]);
    }
}