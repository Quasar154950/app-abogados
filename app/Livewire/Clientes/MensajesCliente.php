<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use App\Models\MensajeCliente as Mensaje;
use Livewire\Component;

class MensajesCliente extends Component
{
    public Cliente $cliente;

    public string $mensaje = '';

    public function enviarMensaje()
    {
        $this->validate([
            'mensaje' => 'required|min:2',
        ]);

        Mensaje::create([
            'cliente_id' => $this->cliente->id,
            'user_id' => auth()->id(),
            'mensaje' => $this->mensaje,
            'remitente' => auth()->user()->role === 'cliente'
                ? 'cliente'
                : 'estudio',
        ]);

        $this->mensaje = '';
    }

    public function vaciarConversacion()
    {
        if (auth()->user()->role !== 'abogado') {
            abort(403);
        }

        $this->cliente->mensajes()->delete();
    }

    public function render()
    {
        $mensajes = $this->cliente
            ->mensajes()
            ->oldest()
            ->get();

        return view('livewire.clientes.mensajes-cliente', [
            'mensajes' => $mensajes,
        ]);
    }
}