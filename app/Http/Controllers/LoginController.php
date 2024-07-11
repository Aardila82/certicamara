<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe y el estado es true (activo)
        if ($user) {
            $usuario = Usuario::where('email', $request->email)->first();
            if ($usuario->estado) {
                if (Auth::attempt($request->only('email', 'password'))) {
                    // La contraseña es correcta y el usuario está activo
                    $request->session()->regenerate();
                    return redirect('dash');
                } else {
                    // Credenciales incorrectas
                    throw ValidationException::withMessages([
                        'email' => __('auth.failed'),
                    ]);
                }
            } else {
                // Usuario inactivo
                throw ValidationException::withMessages([
                    'email' => __('auth.inactive'),
                ]);
            }
        } else {
            // Usuario no encontrado
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

