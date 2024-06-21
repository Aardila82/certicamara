<?php

namespace App\Http\Controllers;

use App\Models\LogFotografia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
        $logs = LogFotografia::all();
        $csvData = [];
        $csvData[] = ['ID', 'FNUT', 'NUIP', 'Peso Real', 'Hash', 'Fotografia']; // Header row

        foreach ($logs as $log) {
            $csvData[] = [
                $log->id,
                $log->fnut,
                $log->nuip,
                $log->peso_real,
                $log->hash,
                $log->fotografia,
            ];
        }

        $filename = "log_fotografia_" . date('Y-m-d_H-i-s') . ".csv";
        $handle = fopen($filename, 'w+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        $headers = [
            'Content-Type' => 'text/csv',
        ];

        return Response::download($filename, $filename, $headers)->deleteFileAfterSend(true);
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
