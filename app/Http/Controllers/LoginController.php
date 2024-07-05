<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usuario;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function index() {
        return view('/login');
    }

    public function store(Request $request)
    {
        // Implementar si es necesario
    }

    public function loginApi(Request $request)
    {
        $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        $user = Usuario::where('email', $request->email)->first();

        // Verificar si el usuario existe, la contraseña es correcta y el estado es 1
        if ($user && Hash::check($request->password, $user->contrasena)) {
            if ($user->estado == 1) {
                // La contraseña es correcta y el usuario está activo
                $request->session()->regenerate();
                return response()->json(['message' => 'Login exitoso', 'user' => $user]);
            } else {
                // Usuario no activo
                return response()->json(['message' => 'Usuario no activo'], 403);
            }
        } else {
            // Credenciales incorrectas
            return response()->json(['message' => 'Email o contraseña incorrectos'], 401);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe y el estado es 1
        if ($user && $user->estado == 1) {
            if (Auth::attempt($request->only('email', 'password'))) {
                // La contraseña es correcta y el usuario está activo
                $request->session()->regenerate();
                return redirect('/dash');
            } else {
                // Credenciales incorrectas
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }
        } else {
            // Usuario no activo o no encontrado
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            } else {
                throw ValidationException::withMessages([
                    'email' => __('auth.inactive'),
                ]);
            }
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
