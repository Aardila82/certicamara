<?php

namespace App\Http\Controllers;

use App\Models\LogCotejoIndividual;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Client;
use Illuminate\Http\Body;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Http;


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

        //return $pdf->download('cedula.pdf');
        return view("cotejo/pdf", ["cedula" => $cedula, "mensaje" => $mensaje, "documento" => $nombrepdf]);
    }


    public function connectliveness(Request $request)
    {
        $nextValue = DB::select('SELECT nextval(\'secuencia_facial\') as value');
        $nut = $nextValue[0]->value;

        /*$log = new LogCotejoIndividual();
        $log->nuip = $request->cedula;
        $log->decision = $request->decision;
        $log->start_time = Carbon::now();
        $log->end_time = $request->end_time;
        $log->finger_number = $request->finger_number;
        $log->user_code = $request->user_code;
        $log->ip_address = $request->ip_address;
        $log->mac_address = $request->mac_address;
        $log->save();*/


        $client = new Client();
        
        $url = 'https://certirostrotst.certicamara.com:7443/v1/colope/oauth2/token';
       
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $headers = [
            'Content-Type' => 'application/json',
            'User-Agent' => $userAgent,
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Connection' => 'keep-alive',
        ];

        $body = [
            'client_id' => 'O3LRM0GJR1YBAZDwz3LDYz6LUvam63g8',
            'client_secret' => 'PDRxi9Ae9txdk8wFT3SBLgC9WYq8RG5u',
            'grant_type' => 'password',
            'provision_key' => 'cCVatCI3pO3hnG7jK7PUf7nMa1r1iiMM',
            'authenticated_userid' => 'certicamara'
        ];

        $response = Http::withHeaders($headers)
        ->withOptions([
            'verify' => false,
            'timeout' => 300, 
            'debug' => false,
            'allow_redirects' => [
                'max' => 5,
                'strict' => false,
                'referer' => false,
                'protocols' => ['http', 'https'],
                'track_redirects' => true
            ],
        ])
        ->post($url, $body);

        $json_rsponse = json_decode($response->body());
        $array_rsponse = (array)$json_rsponse;
        echo "<pre>".print_r($array_rsponse , true)."</pre>";
        $expires_in = 7200;
        $token_type = "bearer";
        $refresh_token = "B2BeX11tkkiHjXCFy1aEg3nXyHPzO2IQ";
        $access_token = $array_rsponse['access_token'];

        //return $response->body();


        $cedula = $request->input('cedula');
        return view("cotejo/idemia", [
            "access_token" => $access_token,
            "cedula" => $cedula
        ]);

    }


    public function downloadPDF($filename)
    {
        $filePath = storage_path('app/pdf/' . $filename);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return abort(404, 'File not found.');
        }
    }
}
