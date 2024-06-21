<?php

namespace App\Http\Controllers;

use App\Models\LogCotejoIndividual;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class LogCotejoIndividualController extends Controller
{
    public function store(Request $request)
    {
        $log = new LogCotejoIndividual();
        $log->nuip = $request->nuip;
        $log->decision = $request->decision;
        $log->start_time = $request->start_time;
        $log->end_time = $request->end_time;
        $log->finger_number = $request->finger_number;
        $log->user_code = $request->user_code;
        $log->ip_address = $request->ip_address;
        $log->mac_address = $request->mac_address;
        $log->save();

        return response()->json(['message' => 'Log stored successfully'], 201);
    }

    public function index()
    {
        $logs = LogCotejoIndividual::all();
       // dd($logs);
        return response()->json($logs);
    }

    public function showLogs()
    {
        $logs = LogCotejoIndividual::all();
        return view('log/logs', compact('logs'));
    }

    public function cotejounoauno(Request $request)
    {

        return view('cotejounoauno');
    }

    public function capturarCedula(Request $request)
    {

         // Leer el contenido del archivo mensaje.txt
        $mensaje = Storage::get('mensaje.txt');
        // Validar la cédula
        $request->validate([
            'cedula' => 'required|numeric',
        ]);

        // Capturar el número de cédula y redirigir a otra vista
        $cedula = $request->input('cedula');
        return view('mostrarcedula', ['cedula' => $cedula, 'mensaje' => $mensaje]);
    }
    public function generarPDF(Request $request)
    {
        $cedula = $request->input('cedula');
        $mensaje = $request->input('mensaje');

        // Generar el PDF
        $pdf = Pdf::loadView('mostrarcedula_pdf', compact('cedula', 'mensaje'));
        return $pdf->download('cedula.pdf');
    }
}
