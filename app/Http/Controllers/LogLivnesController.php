<?php

namespace App\Http\Controllers;

use App\Models\LogLiveness;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogLivnesController extends Controller
{


    public function lista()
    {
        $logs = LogLiveness::get();
        return view('log/liveness')->with('logs', $logs);
    }

    public function exportTxt()
    {
        $results = LogLiveness::all();
        $filename = "liveness.txt";

        return response()->streamDownload(function() use ($results) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'NUT',
                'ID de LIVENESS',
                'NUIP del APLICANTE',    
                'Fecha y hora del proceso de liveness',

                'Clase de liveness',
                'Resultado del liveness detection',
            ]);
            
            foreach ($results as $row) {
                $fecha = Carbon::parse($row->fecha);

                $fecha = substr($fecha->format('Y-m-d H:i:s.u'), 0, -3);

                $array = [
                    $row->nut,
                    $row->id,
                    $row->nuip,

                    $fecha,
                    $row->clase,
                    $row->resultadoLiveness,
                ];
                fwrite($handle, implode(';', $array) . "\n");
            }
    
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
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
    public function show(LogLiveness $logLivnes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogLiveness $logLivnes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogLiveness $logLivnes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogLiveness $logLivnes)
    {
        //
    }
}
