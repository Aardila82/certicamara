<?php

namespace App\Http\Controllers;

use App\Models\usuario;
use App\Models\User;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function lista()
    {
        $usuarios = Usuario::join('departamentos', 'usuarios.departamento', '=', 'departamentos.id')
        ->join('municipios', 'usuarios.municipio', '=', 'municipios.id')
        ->select('usuarios.*', 'departamentos.nombre as departamento_nombre', 'municipios.nombre as municipio_nombre')
        ->get();

    return view('lista')->with('usuarios', $usuarios);
    }
    public function index()
    {
        $roles = DB::table('roles')->get();
        $departamentos = DB::table('departamentos')->orderBy('nombre')->get();
        $usuarios = DB::table('usuarios')
            ->join('departamentos', 'usuarios.departamento', '=', 'departamentos.id')
            ->join('municipios', 'usuarios.municipio', '=', 'municipios.id')
            ->select(
                'usuarios.id',
                'usuarios.nombre1',
                'usuarios.nombre2',
                'usuarios.apellido1',
                'usuarios.apellido2',
                'usuarios.numerodedocumento',
                'usuarios.email',
                'usuarios.telefono',
                'departamentos.nombre as departamento_nombre',
                'municipios.nombre as municipio_nombre',
                'usuarios.estado'
            )
            ->get();

        return view('formulario', [
            'roles' => $roles,
            'departamentos' => $departamentos,
            'usuarios' => $usuarios,
        ]);
    }

    public function getMunicipios($departamento_id)
    {
        $municipios = DB::table('municipios')
            ->where('departamento_id', $departamento_id)
            ->select('municipios.id', 'municipios.nombre')
            ->get();

        return response()->json($municipios);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $campos = $request->all();

        //var_dump($campos );
        //die();

        $request->validate([
            'nombre1' => 'required|string|max:255',
            'nombre2' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'apellido2' => 'required|string|max:255',
            'numerodedocumento' => 'required|unique:usuarios,numerodedocumento|numeric|digits_between:7,10',
            'email' => 'required|unique:usuarios,email|email|max:255',
            'telefono' => 'required|string|max:20',
            'departamento' => 'required|numeric',
            'municipio' => 'required|numeric',
            'usuario' => 'required|unique:usuarios,usuario|string|max:255',
            'contrasena' => 'required|string|max:255|min:8',
            'confirmacion_contrasena' => 'required|string|max:255|same:contrasena',

            'estado' => 'required|boolean',
            'rol' => 'required|numeric',
        ]);

        // Crear un nuevo registro en la base de datos
        $campos["contrasena"] = Hash::Make($campos["contrasena"]);
        Usuario::create($campos);


        User::create(
            [
                'name' => $campos["nombre1"] . " " . $campos["nombre2"] . " " . $campos["apellido1"] . " " . $campos["apellido2"],
                'email' => $campos["email"],
                'password' => $campos["contrasena"],
                'email_verified_at' => Carbon::now(),

            ]
        );
        //Registrra info en la tabla users de laravel para poder hacer  el logueo con auth

        //$request->session()->flash('success', 'Usuario creado exitosamente. ');


        // Redirigir a una ruta específica después de guardar los datos
        return redirect('/guardadoFormulario')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function menu()
    {
        return view('usuario.menu', [
        ]);
    }

    public function reporte()
    {
        return view('usuario.reporte', [
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $roles = DB::table('roles')->get();
        $departamentos = DB::table('departamentos')->orderBy('nombre')->get();
        $municipios = DB::table('municipios')->get();
        $usuario = Usuario::findOrFail($id);
        return view('editarformulario', [
            "roles" => $roles,
            "departamentos" => $departamentos,
            "municipios" => $municipios,
            "usuario" => $usuario
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre1' => 'required|string|max:255',
            'nombre2' => 'nullable|string|max:255',
            'apellido1' => 'required|string|max:255',
            'apellido2' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'departamento' => 'required|numeric',
            'municipio' => 'required|numeric',
            'contrasena' => 'nullable|string|max:255|min:8',
          //  'confirmacion_contrasena' => 'nullable|string|max:255|same:contrasena',
            'estado' => 'boolean',
            'rol' => 'required|numeric',
        ]);

        $usuario = Usuario::findOrFail($id);

        $data = $request->except(['contrasena', 'confirmacion_contrasena']);

        if ($request->filled('contrasena')) {
            $data['contrasena'] = bcrypt($request->contrasena);
        }

        $usuario->update($data);

        return redirect()->route('usuario.editadoformulario')->with('success', 'Usuario actualizado correctamente');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(usuario $usuario)
    {
        //
    }

    public function perfil()
    {
        $usuario = Auth::user();
        return view('perfil', ['usuario' => $usuario]);
    }

    /**
     * Desactivar un usuario
     */
    public function desactivar($id)
    {
        $usuario = Usuario::find($id);
        if ($usuario) {
            $usuario->estado = 0; // Cambia el estado a inactivo
            $usuario->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        }
    }

    public function cambiarEstado(Request $request, $id)
{
    $usuario = Usuario::find($id);
    if ($usuario) {
        $usuario->estado = $usuario->estado ? 0 : 1; // Cambia el estado a inactivo si está activo y viceversa
        $usuario->save();
        return response()->json(['success' => true, 'nuevo_estado' => $usuario->estado]);
    } else {
        return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
    }
}
}


