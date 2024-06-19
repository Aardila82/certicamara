<?php

namespace App\Http\Controllers;

use App\Models\LogFacialEnvivoUnoAUno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogFacialEnvivoUnoAUnoController extends Controller
{

    public function lista($id)
    {
        // Cargamos los registros junto con la relación usuario
        //$logs = LogFacialEnvivoUnoAUno::with('usuario')->get();

        $results = DB::table('log_facial_envivo_uno_a_uno')
        ->leftJoin('usuarios', 'log_facial_envivo_uno_a_uno.idusuario', '=', 'usuarios.id')
        ->leftJoin('alfas', 'log_facial_envivo_uno_a_uno.nuip', '=', 'alfas.pin')
        ->select(
            'log_facial_envivo_uno_a_uno.*',
            'usuarios.nombre1',
            'usuarios.nombre2',
            'usuarios.apellido1',
            'usuarios.apellido2',
            'usuarios.numerodedocumento',
            DB::raw("CONCAT(alfas.nombre1, ' ', alfas.nombre2, '', alfas.apellido1, ' ', alfas.apellido2) as ciudadano"),
            )
            ->where('idmasiva', $id)

        ->get();

        // Pasamos los registros a la vista
        return view('log.facial', [
            'logs' => $results,
            'id' => $id
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = DB::table('log_facial_envivo_uno_a_uno')
            ->join('usuario', 'log_facial_envivo_uno_a_uno.idusuario', '=', 'usuario.id')
            ->join('alfas', 'log_facial_envivo_uno_a_uno.nuip', '=', 'alfas.pin')
            ->select(
                'log_facial_envivo_uno_a_uno.*',
                'usuario.nombre1',
                'usuario.nombre2',
                'usuario.apellido1',
                'usuario.apellido2',
                'usuario.numerodedocumento',
                'alfas.nombre1',
                'alfas.nombre2',
                'alfas.apellido1',
                'alfas.apellido2',

                )
            ->get();


        foreach ($results as $result) {
            echo $result->nombre1 . ' ' . $result->nombre2 . ' ' . $result->apellido1 . ' ' . $result->apellido2 . PHP_EOL;
        }
    }

    public function exportCsv($id)
    {
        $results = DB::table('log_facial_envivo_uno_a_uno')
            ->join('usuarios', 'log_facial_envivo_uno_a_uno.idusuario', '=', 'usuarios.id')
            ->select(
                'log_facial_envivo_uno_a_uno.id',
                'log_facial_envivo_uno_a_uno.nut',
                'log_facial_envivo_uno_a_uno.nuip',
                'log_facial_envivo_uno_a_uno.resultado',
                'log_facial_envivo_uno_a_uno.fechafin',
                'usuarios.nombre1',
                'usuarios.nombre2',
                'usuarios.apellido1',
                'usuarios.apellido2',
                'usuarios.numerodedocumento'
            )
            ->where('idmasiva', $id)
            ->get();

        $filename = "logs_facial.csv";

        return response()->streamDownload(function() use ($results) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID',
                'NUT',
                'NUIP',
                'Resultado',
                'Fecha Fin',
                'Nombre1',
                'Nombre2',
                'Apellido1',
                'Apellido2',
                'Numero de Documento'
            ]);

            foreach ($results as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->nut,
                    $row->nuip,
                    $row->resultado,
                    $row->fechafin,
                    $row->nombre1,
                    $row->nombre2,
                    $row->apellido1,
                    $row->apellido2,
                    $row->numerodedocumento
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
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
