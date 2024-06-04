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

    }

    public function loginApi(Request $request)
    {
        $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

//var_dump($request->all());
        $user = Usuario::where('email', $request->email)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && Hash::check($request->password, $user->contrasena)) {
            // La contraseña es correcta
            $request->session()->regenerate();
            return response()->json(['message' => 'Login exitoso', 'user' => $user]);

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
        //var_dump($user->password);
        //die();
        // Verificar si el usuario existe y la contraseña es correcta
        if ( Auth::attempt($request->only('email', 'password'))) {
            // La contraseña es correcta
            $request->session()->regenerate();
            //return response()->json(['message' => 'Login exitoso', 'user' => $user]);
            return redirect('/dash');


        } else {
            // Credenciales incorrectas
            //return response()->json(['message' => 'Email o contraseña incorrectos'], 401);
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
            return view('login' , []);

        }

        //consultar tabla usuarios por email y contraseña


        //die();
        /*if (auth()->attempt($credentials)) {
            return redirect()->intended('/dash');
        }
        return redirect('/login')->with('error', 'Credenciales incorrectas');*/
    }


    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

}
