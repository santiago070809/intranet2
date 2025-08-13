<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        // Solo permitir a SUPERADMIN
        if (auth()->user()->rol !== 'SUPERADMIN') {
            abort(403, 'No tienes permisos para crear usuarios.');
        }

        $roles = [
            'BIENESTAR Y DESARROLLO INSTITUCIONAL',
            'TALENTO_HUMANO',
            'CONOCIMIENTO',
            'ORGANIZACIONAL',
            'SEGURIDAD_SALUD',
            'TECNOLOGIA',
            'SUPERADMIN'
        ];

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->rol !== 'SUPERADMIN') {
            abort(403, 'No tienes permisos para crear usuarios.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'rol' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'rol' => $request->rol,
            'password' => Hash::make($request->password), // Contraseña personalizada
        ]);

        return redirect()->route('usuarios.create')->with('success', 'Usuario creado con éxito.');
    }

    public function index(Request $request)
    {
        // Solo SUPERADMIN
        if (auth()->user()->rol !== 'SUPERADMIN') {
            abort(403, 'No tienes permisos para administrar usuarios.');
        }

        $search = $request->get('search');

        $usuarios = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('usuarios.index', compact('usuarios', 'search'));
    }

    public function edit($id)
    {
        if (auth()->user()->rol !== 'SUPERADMIN') {
            abort(403);
        }

        $usuario = User::findOrFail($id);
        $roles = [
            'BIENESTAR Y DESARROLLO INSTITUCIONAL',
            'TALENTO_HUMANO',
            'CONOCIMIENTO',
            'ORGANIZACIONAL',
            'SEGURIDAD_SALUD',
            'TECNOLOGIA',
            'SUPERADMIN'
        ];

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->rol !== 'SUPERADMIN') {
            abort(403);
        }

        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol' => 'required|string',
            'password' => 'nullable|min:6'
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;

        if ($request->password) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        if (auth()->user()->rol !== 'SUPERADMIN') {
            abort(403);
        }

        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
