<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 🔐 Si no es admin, cerrar sesión y redirigir
        if (!$user || !$user->is_admin) {
            Auth::logout(); // Cierra la sesión
            return redirect('/login')->with('error', 'Tu sesión ha sido cerrada por acceso no autorizado.');
        }

        $usuarios = User::all();
        return view('admin.usuarios.index', compact('usuarios'));
}
}
