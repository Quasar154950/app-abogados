<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Cliente;

class GestionArchivos extends Component
{
    use WithFileUploads;

    public $model;
    public $archivo;
    public $modo = 'estudio';

    private function verificarAccesoModelo(): void
{
    abort_unless($this->model instanceof Cliente, 403);

    $user = auth()->user();

    abort_unless($user, 403);

    if ($this->modo === 'cliente') {
        abort_unless(
            $user->role === 'cliente' &&
            (int) $this->model->user_id === (int) $user->id,
            403
        );

        return;
    }

    abort_unless(
        $user->role === 'abogado' &&
        (int) $this->model->abogado_id === (int) $user->id,
        403
    );
}

    public function mount($model, $modo = 'estudio')
    {
        $this->model = $model;
        $this->modo = $modo;
    }

    public function guardarArchivo()
    {

        $this->verificarAccesoModelo();

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
            ->fresh()
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

        $subidoPor = $this->modo === 'cliente' ? 'cliente' : 'estudio';

        $revisadoPorEstudio = $this->modo === 'cliente' ? false : true;

        $this->model->addMedia($this->archivo->getRealPath())
            ->usingName($nombreOriginal)
            ->usingFileName($nombreFinal)
            ->withCustomProperties([
                'subido_por' => $subidoPor,
                'revisado_por_estudio' => $revisadoPorEstudio,
            ])
            ->toMediaCollection('archivos', 'cloudinary');

        $this->archivo = null;

        $this->model = $this->model->fresh();

        if ($this->modo === 'cliente') {
            session()->flash('success', '✅ ¡Documento enviado al estudio con éxito!');
        } else {
            session()->flash('success', '✅ ¡Archivo guardado con éxito!');
        }
    }

    public function eliminarArchivo($id)
{
        $this->verificarAccesoModelo();

        $media = $this->model
            ->getMedia('archivos')
            ->firstWhere('id', (int) $id);

        if ($media) {
            $media->delete();

            $this->model = $this->model->fresh();

            session()->flash(
                'success',
                '🗑️ Archivo eliminado correctamente.'
            );
        }
    }
    
    public function marcarComoVisto($id)
{
        $this->verificarAccesoModelo();

        $media = $this->model
            ->getMedia('archivos')
            ->firstWhere('id', (int) $id);

    if (! $media) {
        return;
    }

    // Solo marcamos como visto cuando el documento fue subido por el cliente
    if ($media->getCustomProperty('subido_por') !== 'cliente') {
        return;
    }

    // Si ya fue visto antes, no tocamos nada
    if ($media->viewed_at) {
        return;
    }

    $media->update([
        'viewed_at' => now(),
    ]);

    $this->model = $this->model->fresh();
}

    public function render()
    {

        $this->verificarAccesoModelo();

        $modelActualizado = $this->model->fresh();

        return view('livewire.actions.gestion-archivos', [
            'archivos' => $modelActualizado->getMedia('archivos'),
            'modo' => $this->modo,
        ]);
    }
}