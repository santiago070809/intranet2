<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            // Guardar en sesión el usuario autenticado
            session(['user' => auth()->user()]);

            return redirect()->route('home'); // o la ruta protegida que tengas
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout(); // ✅ Cierra sesión correctamente
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
