<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Usuario;
use App\Models\DatosConexion;
use  Jenssegers\Agent\Agent;


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

        $ubicacion_geografica = $request->latitude." ".$request->longitude;


        // Verificar si el usuario existe y el estado es true (activo)
        if ($user) {
            $usuario = Usuario::where('email', $request->email)->first();
            if ($usuario->estado) {
                if (Auth::attempt($request->only('email', 'password'))) {
                    // La contraseña es correcta y el usuario está activo
                    $request->session()->regenerate();
                    $agent = new Agent();

                    $datosConexion = new DatosConexion();
                    $datosConexion->so = $agent->platform();
                    $datosConexion->navegador = $agent->browser();
                    $datosConexion->ubicacion_geografica = $ubicacion_geografica; // Necesitarías usar una API externa
                    $datosConexion->ipv4 = $_SERVER['REMOTE_ADDR'];

                    $datosConexion->identificador_unico_dispositivo = 'No disponible'; // Requiere un enfoque más avanzado
                    $datosConexion->fecha = now();
            
                    // Guardar la información en la base de datos
                    $datosConexion->save();                    

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

