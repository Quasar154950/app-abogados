<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Etiqueta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClienteController extends Controller
{
    /**
     * Muestra el listado de clientes activos con paginación.
     */
    public function index()
    {
        $clientes = Cliente::where('archivado', false)->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Muestra el listado de clientes ARCHIVADOS.
     */
    public function archivados()
    {
        $clientes = Cliente::where('archivado', true)->paginate(10);

        return view('clientes.archivados', compact('clientes'));
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Guarda un nuevo cliente en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clientes,email',
            'direccion' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email no es válido.',
            'email.unique' => 'El email ya está registrado.',
            'direccion.required' => 'La dirección es obligatoria.',
        ]);

        Cliente::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'archivado' => false,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Muestra el detalle del cliente, sus notas y seguimientos.
     */
    public function show(Request $request, string $id)
    {
        $cliente = Cliente::with([
            'user',
            'notas',
            'seguimientos.etiqueta',
        ])->findOrFail($id);

        $etiquetas = Etiqueta::all();
        $usuarios = User::where('role', 'cliente')->get();

        $estadoFiltro = $request->query('estado');
        $hoy = Carbon::today();

        $stats = [
            'todos' => $cliente->seguimientos->count(),
            'pendiente' => $cliente->seguimientos->where('estado', 'pendiente')->count(),
            'en_curso' => $cliente->seguimientos->where('estado', 'en_curso')->count(),
            'resuelto' => $cliente->seguimientos->where('estado', 'resuelto')->count(),
        ];

        $seguimientosFiltrados = $cliente->seguimientos
            ->when($estadoFiltro, function ($collection) use ($estadoFiltro) {
                return $collection->where('estado', $estadoFiltro);
            })
            ->sortBy(function ($s) use ($hoy) {
                $prioEstado = ($s->estado === 'resuelto') ? 1 : 0;

                if ($s->fecha_recordatorio) {
                    $fecha = Carbon::parse($s->fecha_recordatorio)->startOfDay();

                    if ($fecha->lt($hoy)) {
                        $prioFecha = 0;
                    } elseif ($fecha->isSameDay($hoy)) {
                        $prioFecha = 1;
                    } else {
                        $prioFecha = 2;
                    }
                } else {
                    $prioFecha = 3;
                }

                $prioImportancia = match ($s->prioridad) {
                    'alta' => 0,
                    'media' => 1,
                    'baja' => 2,
                    default => 1,
                };

                return [$prioEstado, $prioFecha, $prioImportancia, -$s->created_at->timestamp];
            });

        return view('clientes.show', compact(
            'cliente',
            'seguimientosFiltrados',
            'stats',
            'estadoFiltro',
            'etiquetas',
            'usuarios'
        ));
    }

    /**
     * Asigna un usuario cliente ya existente al cliente.
     */
    public function asignarUsuario(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
        ]);

        $cliente = Cliente::findOrFail($id);

        $cliente->update([
            'user_id' => $request->user_id,
        ]);

        return back()->with('success', 'Usuario asignado correctamente.');
    }

    /**
     * Crea un usuario cliente nuevo y lo vincula automáticamente al cliente.
     */
    public function crearAcceso(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->user_id) {
            return back()->with('error', 'Este cliente ya tiene un acceso creado.');
        }

        $request->validate([
            'email_acceso' => 'required|email|max:255|unique:users,email',
            'password_acceso' => 'required|string|min:6|confirmed',
        ], [
            'email_acceso.required' => 'El email de acceso es obligatorio.',
            'email_acceso.email' => 'El email de acceso no es válido.',
            'email_acceso.unique' => 'Ese email ya está en uso.',
            'password_acceso.required' => 'La contraseña es obligatoria.',
            'password_acceso.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password_acceso.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        $user = User::create([
            'name' => $cliente->nombre,
            'email' => $request->email_acceso,
            'password' => $request->password_acceso,
            'role' => 'cliente',
        ]);

        $cliente->update([
            'user_id' => $user->id,
        ]);

        return back()->with('success', 'Acceso del cliente creado y vinculado correctamente.');
    }

    /**
     * Restablece la contraseña del usuario asociado al cliente.
     */
    public function resetPassword(string $id)
    {
        $cliente = Cliente::with('user')->findOrFail($id);

        if (!$cliente->user) {
            return back()->with('error', 'Este cliente no tiene un usuario asociado.');
        }

        $nuevaPassword = Str::password(10);

        $cliente->user->update([
            'password' => $nuevaPassword,
        ]);

        return back()
            ->with('success', 'Contraseña restablecida correctamente.')
            ->with('nueva_password', $nuevaPassword);
    }

    /**
     * Quita el acceso del cliente eliminando el usuario vinculado.
     */
    public function quitarAcceso(string $id)
    {
        $cliente = Cliente::with('user')->findOrFail($id);

        if (!$cliente->user) {
            return back()->with('error', 'Este cliente no tiene acceso vinculado.');
        }

        $user = $cliente->user;

        $cliente->update([
            'user_id' => null,
        ]);

        $user->delete();

        return back()->with('success', 'Acceso del cliente eliminado correctamente.');
    }

    /**
     * Guarda un nuevo seguimiento asociado al cliente.
     */
    public function storeSeguimiento(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'descripcion' => 'required|string',
            'etiqueta_id' => 'nullable|exists:etiquetas,id',
        ]);

        $cliente->seguimientos()->create([
            'descripcion' => $request->descripcion,
            'etiqueta_id' => $request->etiqueta_id,
            'user_id' => auth()->id() ?? 1,
            'estado' => 'pendiente',
            'prioridad' => $request->prioridad ?? 'media',
            'fecha_recordatorio' => $request->fecha_recordatorio,
        ]);

        return back()->with('success', 'Seguimiento registrado con éxito.');
    }

    /**
     * Muestra el formulario para editar un cliente.
     */
    public function edit(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualiza los datos del cliente.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clientes,email,' . $id,
            'direccion' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email no es válido.',
            'email.unique' => 'El email ya está registrado.',
            'direccion.required' => 'La dirección es obligatoria.',
        ]);

        $cliente = Cliente::findOrFail($id);

        $cliente->update([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Elimina un cliente.
     */
    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }

    /**
     * Archiva un cliente.
     */
    public function archivar(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update(['archivado' => true]);

        return redirect()->route('clientes.index')->with('success', 'Cliente archivado correctamente.');
    }

    /**
     * Desarchiva un cliente.
     */
    public function desarchivar(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update(['archivado' => false]);

        return redirect()->route('clientes.archivados')->with('success', 'Cliente restaurado correctamente.');
    }
}