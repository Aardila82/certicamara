<?php

namespace App\Http\Controllers;

use App\Models\LogFacialEnvivoUnoAUno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogFacialEnvivoUnoAUnoController extends Controller
{

    public function lista()
    {
        // Cargamos los registros junto con la relación usuario
        $logs = LogFacialEnvivoUnoAUno::with('usuario')->get();

        // Pasamos los registros a la vista
        return view('log.facial', compact('logs'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = DB::table('log_facial_envivo_uno_a_uno')
            ->join('usuario', 'log_facial_envivo_uno_a_uno.idusuario', '=', 'usuario.id')
            ->select(
                'log_facial_envivo_uno_a_uno.*',
                'usuario.nombre1',
                'usuario.nombre2',
                'usuario.apellido1',
                'usuario.apellido2',
                'usuario.numerodedocumento'
            )
            ->get();

        foreach ($results as $result) {
            echo $result->nombre1 . ' ' . $result->nombre2 . ' ' . $result->apellido1 . ' ' . $result->apellido2 . PHP_EOL;
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }
}
