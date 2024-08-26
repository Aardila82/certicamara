<?php

namespace App\Http\Controllers;

use App\Models\LogFotografia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;


class LogFotografiaController extends Controller
{

    public function lista()
    {
        $logs = LogFotografia::get();
        return view('log/fotografia')->with('logs', $logs);
    }
    /**
     * Display a listing of the resource.
     */
    public function exportCsv()
    {
        /*$results = LogFotografia::all();
        $filename = "fotoscotejo.txt";

        return response()->streamDownload(function() use ($results) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'NUT',
                'NUIP criterio de busqueda',    
                'Peso en Kb',

                'Hash SHA256',
                'Fotografia',
            ]);
            
            foreach ($results as $row) {
                $array = [
                    $row->nut,
                    $row->nuip,
                    $row->peso_real,

                    $row->hash,
                    $row->fotografia,
                ];
                fwrite($handle, implode(';', $array) . "\n");
            }
    
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);*/

        $results = LogFotografia::all();
        $filename = "fotoscotejo.txt";
        
        // Ruta donde se guardará el archivo en el servidor
        $path = storage_path("app/logs/$filename");

        $handle = fopen($path, 'w');
        $header = [
            'NUT',
            'NUIP criterio de busqueda',
            'Peso en Kb',
            'Hash SHA256',
            'Fotografia',
        ];
        fwrite($handle, implode(';', $header) . "\n");

        foreach ($results as $row) {
            $array = [
                $row->nut,
                $row->nuip,
                $row->peso_real,
                $row->hash,
                $row->fotografia,
            ];
            fwrite($handle, implode(';', $array) . "\n");
        }

        // Cierra el archivo en el servidor
        fclose($handle);
    
        // Ahora abre el archivo que acabas de guardar en el servidor para su descarga
        $handle = fopen($path, 'r');
        fpassthru($handle);

        // Cierra el archivo de la descarga
        fclose($handle);

        return response()->streamDownload(function() use ($results, $path) {
            // Abre el archivo en la ruta especificada para escribirlo

        }, $filename, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
        
    }
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
