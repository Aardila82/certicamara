<?php

namespace App\Http\Controllers;

use App\Models\LogCotejoIndividual;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Client;



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
        $filePath = storage_path('app/mensaje.txt');
        $mensaje = File::get($filePath);

        // Validar la cédula
        $request->validate([
            'cedula' => 'required|numeric|digits_between:8,10',
        ], [
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.numeric' => 'La cédula debe ser un número.',
            'cedula.digits_between' => 'La cédula debe tener entre 6 y 10 dígitos.',
        ]);

        // Capturar el número de cédula y redirigir a otra vista
        $cedula = $request->input('cedula');
        return view('mostrarcedula', ['cedula' => $cedula, 'mensaje' => $mensaje]);
    }

    public function generarPDF(Request $request)
    {


                
        /*$client = new Client();

        $url = 'idemia.com';

        $body = [
            'client_id' => 'xB5tDfx6fv3nv9qTflyaXWkJNWqMGAPo',
            'client_secret' => 'NIr7J9CfsdfSDEVChQC3FZ',
            'grant_type' => 'password',
            'provision_key' => 'iwT5kIr7J9CfflyaXr0qYvq3M04y2836R',
            'authenticated_userid' => 'cliente'
        ];

        try {
            $response = $client->post($url, [
                'json' => $body
            ]);

            $responseBody = $response->getBody()->getContents();

            $data = json_decode($responseBody, true);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }*/


        $cedula = $request->input('cedula');
        $mensaje = $request->input('mensaje');

        $folderPath = storage_path('app/pdf');
    
        // Verificar si la carpeta existe, y si no, crearla
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        

        // Generar el PDF
        $pdf = Pdf::loadView('mostrarcedula_pdf', compact('cedula', 'mensaje'));
        $time = time();
        $nombrepdf = $cedula."_".$time.".pdf";
        //$filePath = storage_path('app/public/'.$nombre_pdf);
        Storage::put('pdf/'.$nombrepdf, $pdf->output());

        echo "";

        //return $pdf->download('cedula.pdf');
    }
}
