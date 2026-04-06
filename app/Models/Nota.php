<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Traits\RegistraActividad;

class Nota extends Model
{
    use RegistraActividad;

    protected $table = 'notas';

    protected $fillable = ['cliente_id', 'contenido', 'is_pinned'];

    // --- NUEVO: Identificador humano para el historial ---
    public function getLogNombre()
    {
        // Esto busca el nombre del cliente a través de la relación
        return $this->cliente->nombre ?? 'Sin Cliente';
    }

    // --- Traductor de valores para el historial ---
    public function traducirAtributo($campo, $valor)
    {
        if ($campo === 'is_pinned') {
            return $valor ? '📌 Nota fijada' : '📍 Nota normal (sin pin)';
        }
        
        return $valor;
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
