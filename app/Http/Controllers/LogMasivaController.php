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
        /*$logs = DB::table('log_masivas')
            ->join('usuarios', 'log_masivas.usuariocarga_id', '=', 'usuarios.id')
            ->select(
                'log_masivas.*',
                DB::raw("CONCAT(usuarios.nombre1, ' ', usuarios.nombre2, ' ', usuarios.apellido1, ' ', usuarios.apellido2) as usuario_carga"),
                DB::raw("ROUND(EXTRACT(EPOCH FROM (CAST(fechafin AS timestamp) - CAST(fechainicio AS timestamp)))::NUMERIC, 2) AS diferencia_segundos")
            )
            ->get();*/
            /*$logs = DB::table('log_masivas')
            ->leftJoin('log_facial_envivo_uno_a_uno as lf', 'log_masivas.id', '=', 'lf.idmasiva')
            ->join('usuarios', 'log_masivas.usuariocarga_id', '=', 'usuarios.id')

            ->select('log_masivas.*',
                //DB::raw("CONCAT(usuarios.nombre1, ' ', usuarios.nombre2, ' ', usuarios.apellido1, ' ', usuarios.apellido2) as usuario_carga"),

                DB::raw('SUM(CASE WHEN lf.resultado = \'Hit\' THEN 1 ELSE 0 END) AS total_hit'),
                DB::raw('SUM(CASE WHEN lf.resultado = \'No Hit\' THEN 1 ELSE 0 END) AS total_nohit')
            )
            ->groupBy('log_masivas.id')
            ->orderBy('log_masivas.id', 'desc')
            ->get();*/

            $logs = DB::table('log_masivas')
     
                ->join('view_log_facial_total_hits as vfs', 'log_masivas.id', '=', 'vfs.idmasiva')
                ->join('usuarios', 'log_masivas.usuariocarga_id', '=', 'usuarios.id')

                ->select('log_masivas.*', 
                    'vfs.total_hit', 'vfs.total_nohit',
                    DB::raw("CONCAT(usuarios.nombre1, ' ', usuarios.nombre2, ' ', usuarios.apellido1, ' ', usuarios.apellido2) as usuario_carga"),
                    DB::raw("ROUND(EXTRACT(EPOCH FROM (CAST(fechafin AS timestamp) - CAST(fechainicio AS timestamp)))::NUMERIC, 2) AS diferencia_segundos")
                )
                ->get();

            //var_dump($logs);
        return view('log/masiva', ['logs' => $logs]);
    }

    public function createZip()
    {
        $zip = new \ZipArchive();
        $fileName = 'example.zip';
        $filePath = public_path('files'); // Ruta a los archivos

        // Obtener la lista de logs
        $logs = DB::table('log_masivas')
            ->join('usuarios', 'log_masivas.usuariocarga_id', '=', 'usuarios.id')
            ->select(
                'log_masivas.*',
                DB::raw("CONCAT(usuarios.nombre1, ' ', usuarios.nombre2, ' ', usuarios.apellido1, ' ', usuarios.apellido2) as usuario_carga"),
                DB::raw("ROUND(EXTRACT(EPOCH FROM (CAST(fechafin AS timestamp) - CAST(fechainicio AS timestamp)))::NUMERIC, 2) AS diferencia_segundos")
            )
            ->get();

        // Crear el archivo CSV temporalmente
        $csvFileName = 'logs.csv';
        $csvFilePath = $filePath . '/' . $csvFileName;
        $file = fopen($csvFilePath, 'w');

        // Encabezados del CSV
        fputcsv($file, ['ID', 'Usuario Carga', 'Fecha Inicio', 'Fecha Fin', 'Diferencia Segundos']);

        // Agregar los registros al CSV
        foreach ($logs as $log) {
            fputcsv($file, [
                $log->id,
                $log->usuario_carga,
                $log->fechainicio,
                $log->fechafin,
                $log->diferencia_segundos,
            ]);
        }

        fclose($file);

        if ($zip->open(public_path($fileName), \ZipArchive::CREATE) === TRUE) {
            // Agregar el archivo CSV al ZIP
            if (file_exists($csvFilePath)) {
                $zip->addFile($csvFilePath, $csvFileName);
            } else {
                return response()->json(['error' => 'No se pudo encontrar el archivo CSV'], 404);
            }

            $zip->close();
        } else {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
        }

        // Eliminar el archivo CSV temporal
        unlink($csvFilePath);

        return response()->download(public_path($fileName));
    }

    // Otros métodos...

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
