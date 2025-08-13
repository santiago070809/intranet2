<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        return view('perfil.index', compact('usuario'));
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password_nueva' => 'required|string|min:6|confirmed'
        ]);

        $usuario = Auth::user();

        // Verificar contraseña actual
        if (!Hash::check($request->password_actual, $usuario->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
        }

        // Cambiar contraseña
        $usuario->password = Hash::make($request->password_nueva);
        $usuario->save();

        return back()->with('success', 'Contraseña actualizada con éxito.');
    }
}
