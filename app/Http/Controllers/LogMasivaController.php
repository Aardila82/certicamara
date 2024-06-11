<?php

namespace App\Http\Controllers;

use App\Models\LogFotografia;
use App\Models\LogMasiva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogMasivaController extends Controller
{

    public function lista()
    {
        //$logs = LogMasiva::get();

        $logs = DB::table('log_masivas')
        ->join('usuarios', 'log_masivas.usuariocarga_id', '=', 'usuarios.id')
        ->select(
            'log_masivas.*',
            DB::raw("CONCAT(usuarios.nombre1, ' ', usuarios.nombre2, ' ', usuarios.apellido1, ' ', usuarios.apellido2) as usuario_carga"),
            DB::raw("ROUND(EXTRACT(EPOCH FROM (fechafin - fechainicio))::NUMERIC, 2) AS diferencia_segundos"),

            )
        ->get();
        
           
        return view('log/masiva' , ['logs' => $logs]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(LogFotografia $logFacialDos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogFotografia $logFacialDos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogFotografia $logFacialDos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogFotografia $logFacialDos)
    {
        //
    }
}
