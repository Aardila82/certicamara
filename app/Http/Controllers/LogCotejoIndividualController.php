<?php

namespace App\Http\Controllers;

use App\Models\LogCotejoIndividual;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Services\Matcher;
use App\Services\Coordenadas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LogFacialEnvivoUnoAUno;
use App\Models\LogFotografia;
use App\Models\LogLiveness;
use App\Models\Atdp;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;





use Illuminate\Support\Facades\Http;


class LogCotejoIndividualController extends Controller
{

    private $usuario;
        
    public function __construct(){
        $this->usuario = Auth::user();
    }


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

    public function cotejounoauno()
    {

        return view('cotejo/consultar');
    }

    public function capturarCedula(Request $request)
    {
        // Leer el contenido del archivo mensaje.txt
        $filePath = storage_path('app/mensaje.txt');
        $mensaje = File::get($filePath);

        // Validar la cédula
        $request->validate([
            'cedula' => 'required|numeric|digits_between:6,10',
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
        $estadoAprobacion = "APROBADO";

        $filePath = storage_path('app/mensaje.txt');
        $mensaje = File::get($filePath);
        $folderPath = storage_path('app/pdf');

        // Verificar si la carpeta existe, y si no, crearla
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Generar el PDF
        //$pdf = Pdf::loadView('mostrarcedula_pdf', compact('cedula', 'mensaje'));

        $pdf = Pdf::loadView('mostrarcedula_pdf', [
            'cedula' => $cedula,
            'mensaje' => $mensaje,
            'estadoAprobacion' => $estadoAprobacion,
        ]);



        $time = time();
        $nombrepdf = $cedula."_".$time.".pdf";
        //$filePath = storage_path('app/public/'.$nombre_pdf);
        Storage::put('pdf/'.$nombrepdf, $pdf->output());

        //return $pdf->download('cedula.pdf');
        return view("cotejo/pdf", ["cedula" => $cedula, "mensaje" => $mensaje, "documento" => $nombrepdf]);
    }


    public function connectliveness($cedula , Request $request)
    {
        $cedulaResponse = $cedula;

        // Obtener el siguiente valor de la secuencia
        $nextValue = DB::select('SELECT nextval(\'secuencia_facial\') as value');
        $nut = $nextValue[0]->value;
        $url = 'https://certirostrotst.certicamara.com:7443/v1/colope/oauth2/token';
        $urlQr = url('connectliveness/qr/'.$cedula);
        
        $os = $this->detectOS();
        if($os != "Android"){
            $codeQrBase64 = $this->generate($urlQr);
            return view('cotejo/qr', [
                'qr' => $codeQrBase64,
                'url' => $urlQr,

            ]);

        }
        
        // Configurar el cliente HTTP
        $client = new \GuzzleHttp\Client();

        // Definir la URL y el agente de usuario
        $userAgent = $request->header('User-Agent');
        $userAgent = 'PostmanRuntime/7.40.0';

        // Definir los encabezados de la solicitud
        $headers = [
            'Content-Type' => 'application/json',
            'User-Agent' => $userAgent,
            'Accept' => '/',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Connection' => 'keep-alive',
        ];

        // Definir el cuerpo de la solicitud
        $body = [
            'client_id' => 'O3LRM0GJR1YBAZDwz3LDYz6LUvam63g8',
            'client_secret' => 'PDRxi9Ae9txdk8wFT3SBLgC9WYq8RG5u',
            'grant_type' => 'password',
            'provision_key' => 'cCVatCI3pO3hnG7jK7PUf7nMa1r1iiMM',
            'authenticated_userid' => 'certicamara'
        ];

        // Hacer la solicitud POST
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


        // Procesar la respuesta
        $json_response = json_decode($response->body(), true);
        if (isset($json_response['access_token'])) {
            $access_token = $json_response['access_token'];
        } else {
            throw new \Exception('Error retrieving access token');
        }

        // Obtener el valor de la cédula desde la solicitud
        $cedula = $request->input('cedula');

        // Renderizar la vista con los datos obtenidos
        return view("cotejo/idemia", [
            "access_token" => $access_token,
            "cedula" => $cedula
        ]);
        
        /*try {
            // Obtener el siguiente valor de la secuencia
            $nextValue = DB::select('SELECT nextval(\'secuencia_facial\') as value');
            $nut = $nextValue[0]->value;

            // Configurar el cliente HTTP
            $client = new \GuzzleHttp\Client();

            // Definir la URL y el agente de usuario
            $url = 'https://certirostrotst.certicamara.com:7443/v1/colope/oauth2/token';
            $userAgent = $request->header('User-Agent');

            // Definir los encabezados de la solicitud
            $headers = [
                'Content-Type' => 'application/json',
                'User-Agent' => $userAgent,
                'Accept' => '/',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
            ];

            // Definir el cuerpo de la solicitud
            $body = [
                'client_id' => 'O3LRM0GJR1YBAZDwz3LDYz6LUvam63g8',
                'client_secret' => 'PDRxi9Ae9txdk8wFT3SBLgC9WYq8RG5u',
                'grant_type' => 'password',
                'provision_key' => 'cCVatCI3pO3hnG7jK7PUf7nMa1r1iiMM',
                'authenticated_userid' => 'certicamara'
            ];

            // Hacer la solicitud POST
            $response = Http::withHeaders($headers)
                ->withOptions([
                    'verify' => true,
                    'timeout' => 10,
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

            // Procesar la respuesta
            $json_response = json_decode($response->body(), true);
            if (isset($json_response['access_token'])) {
                $access_token = $json_response['access_token'];
            } else {
                throw new \Exception('Error retrieving access token');
            }

            // Obtener el valor de la cédula desde la solicitud
            $cedula = $request->input('cedula');

            // Renderizar la vista con los datos obtenidos
            return view("cotejo/idemia", [
                "access_token" => $access_token,
                "cedula" => $cedula
            ]);

        } catch (\Exception $e) {

            $nextValue = DB::select('SELECT nextval(\'secuencia_facial\') as value');
            $nut = $nextValue[0]->value;
            $estadoAprobacion = "APROBADO";
            $fechaActual = Carbon::now()->format('Y-m-d H:i:s');

            //Generar PDF
            $filePath = storage_path('app/mensaje.txt');
            $mensaje = File::get($filePath);
            $folderPath = storage_path('app/pdf');
    
            // Verificar si la carpeta existe, y si no, crearla
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
    

            $pdf = Pdf::loadView('mostrarcedula_pdf', [
                'cedula' => $cedula,
                'mensaje' => $mensaje,
                'estadoAprobacion' => $estadoAprobacion,
                'fechaActual' => $fechaActual,
            ]);
            
            $time = time();
            $nombrepdf = $cedula."_".$time.".pdf";
            $atdpRuta = $nombrepdf;

            //$filePath = storage_path('app/public/'.$nombre_pdf);
            Storage::put('pdf/'.$nombrepdf, $pdf->output());
            
            
            // Verificar si la carpeta 'cand' existe
            $directory = storage_path('app/cand');
            if (!File::exists($directory)) {
                // Crear la carpeta si no existe (solo por seguridad)
                File::makeDirectory($directory, 0755, true);
            }

            // Obtener todas las imágenes en la carpeta 'cand'

            $fotosPath = "app/cand";
            $directoryFotosPath = storage_path($fotosPath);
            $directories = File::directories($directoryFotosPath);

            // Verificar si hay imágenes
            if (count($directories) > 0) {

                // Seleccionar una imagen aleatoriamente
                $randomDir = $directories[array_rand($directories)];
                $files = File::files($randomDir);
                //var_dump($files[0]);
                $randomFile = $files[0]->getPathname();
                $fileSizeInBytes = filesize($randomFile);

                $imageData = file_get_contents($randomFile);
                $sha256 = hash('sha256', $imageData);
                $base64 = base64_encode($imageData);
                $type = pathinfo($randomFile, PATHINFO_EXTENSION);
                $randomImageBase64 = 'data:image/' . $type . ';base64,'.$base64;
                // Convertir el tamaño a kilobytes
                $fileSizeInKilobytes = $fileSizeInBytes / 1024;

                // Mostrar el tamaño en kilobytes
                $fileSizeInKilobytes =  round($fileSizeInKilobytes, 2);
                // Obtener el nombre del archivo
                $randomImage = basename($randomFile);
                $cedula = explode("." , $randomImage)[0];
                $coordenadas = new Coordenadas();
                $coordenadasData = $coordenadas->getCoordenadas();
                $latitud = $coordenadasData['latitud'];
                $longitud = $coordenadasData['longitud'];
                $logFotografiaArray = [
                    'nut' => $nut,
                    'nuip' => $cedula,
                    'peso_real'=> $fileSizeInKilobytes,
                    'hash' => $sha256,
                    'fotografia' => ($base64)
                ];
                $idFotografia = LogFotografia::create($logFotografiaArray);

                                // Definir la ruta de almacenamiento
                $filePath = 'img11/' . $cedula. ".jpg";

                // Guardar la imagen en la carpeta storage/app/img11
                Storage::put($filePath, $base64);

                                

                $dtini = Carbon::now();
                $fecha = $dtini->format('Y-m-d H:i:s.u');
                
                //Log Liveness
                $logLivenessArray = [
                    'nut' => $nut,
                    'nuip' => $cedula,
                    'fecha' => $fecha,
        
                    'clase' => 0,
                    'resultadoLiveness'  => 'DETECTADO',
    
                ];
                $idliveness = LogLiveness::create($logLivenessArray);
                
                $matcher = new Matcher();
                $matcherResponse = $matcher->connect($cedula, 0, $base64, $sha256, $latitud, $longitud, $atdpRuta);
                $matcherResponse['nuip'] = $cedulaResponse;

                //Crear el registro en la tabla Atdp
                $atdp = new Atdp();
                $atdp->nut = $nut;
                $atdp->estado_aprobacion = $estadoAprobacion;
                $atdp->cedula_aprobacion = $cedula;
                $atdp->enlace_atdp = $atdpRuta;
                $atdp->save();

            } else {
                // Si no hay imágenes, usar una imagen por defecto
                $randomImage = 'nueva1.jpg'; // asegúrate de tener una imagen por defecto en la carpeta 'cand'
                die("No hay imagenes");
            }

            // Redirigir a la vista de error con la imagen seleccionada
            return view('error', [
                'randomImage' => $randomImage, 
                'randomImageBase64' => $randomImageBase64,
                'data' => $matcherResponse,
            ]);
        }*/
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


    public function rechazarcotejo($cedula)
    {
        $nextValue = DB::select('SELECT nextval(\'secuencia_facial\') as value');
        $nut = $nextValue[0]->value;
        $estadoAprobacion = "RECHAZADO";
        $fechaActual = Carbon::now()->format('Y-m-d H:i:s');

        //Generar PDF
        $filePath = storage_path('app/mensaje.txt');
        $mensaje = File::get($filePath);
        $folderPath = storage_path('app/pdf');

        // Verificar si la carpeta existe, y si no, crearla
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $pdf = Pdf::loadView('mostrarcedula_pdf', [
            'cedula' => $cedula,
            'mensaje' => $mensaje,
            'estadoAprobacion' => $estadoAprobacion,
            'fechaActual' => $fechaActual,
        ]);

                
        $time = time();
        $nombrepdf = $cedula."_".$time.".pdf";
        $atdpRuta = $nombrepdf;

        //$filePath = storage_path('app/public/'.$nombre_pdf);
        Storage::put('pdf/'.$nombrepdf, $pdf->output());


        // Insertar los datos usando Eloquent
        // Insertar en la tabla log_facial_envivo_uno_a_uno
        $logData = [
            'nut' => $nut, // Ejemplo de asignación, ajusta según sea necesario
            'nuip' => $cedula, // Ejemplo de asignación, ajusta según sea necesario
            'resultado' => '', // Ejemplo de valor estático, ajusta según sea necesario
            'fechafin' => Carbon::now(), // Usar la fecha actual
            'idusuario' => $this->usuario->id, // ID del usuario actual o cualquier otro valor
            'hash256' => '', // Ejemplo de cálculo hash
            'idmasiva' => 0,
            'atdpruta' => $atdpRuta,
            'aprobacion_atdp' => 0,

            //'response' => json_encode($response),
        ];

        //Creacion registro ATDP
        $atdp = new Atdp();
        $atdp->nut = $nut;
        $atdp->estado_aprobacion = $estadoAprobacion;
        $atdp->cedula_aprobacion = $cedula;
        $atdp->enlace_atdp = $atdpRuta;
        $atdp->save();

        $logData['usuarioNombre'] = $this->usuario->name;
        $idUnoAUno = LogFacialEnvivoUnoAUno::create($logData);

        return redirect()->to('cotejounoauno/consultar');
    }



    public function generate($data)
    {
        // Generar el código QR
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($data)  // Cambia esto por el dato que quieres codificar
            ->size(300)
            ->margin(10)
            ->build();

        // Guardar la imagen del código QR en storage/app/public/qrcode.png
        $qrImage = $result->getString();

        // Convertir la cadena binaria a base64
        $qrImageBase64 = base64_encode($qrImage);

        return $qrImageBase64;
    }
    


    function detectOS() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $osPlatform = "Sistema Operativo Desconocido";
    
        $osArray = [
            '/windows nt 10/i'      => 'Windows 10',
            '/windows nt 6.3/i'     => 'Windows 8.1',
            '/windows nt 6.2/i'     => 'Windows 8',
            '/windows nt 6.1/i'     => 'Windows 7',
            '/windows nt 6.0/i'     => 'Windows Vista',
            '/windows nt 5.1/i'     => 'Windows XP',
            '/windows xp/i'         => 'Windows XP',
            '/windows nt 5.0/i'     => 'Windows 2000',
            '/windows me/i'         => 'Windows ME',
            '/win98/i'              => 'Windows 98',
            '/win95/i'              => 'Windows 95',
            '/win16/i'              => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i'        => 'Mac OS 9',
            '/linux/i'              => 'Linux',
            '/ubuntu/i'             => 'Ubuntu',
            '/iphone/i'             => 'iPhone',
            '/ipod/i'               => 'iPod',
            '/ipad/i'               => 'iPad',
            '/android/i'            => 'Android',
            '/blackberry/i'         => 'BlackBerry',
            '/webos/i'              => 'Mobile'
        ];
    
        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
            }
        }
    
        return $osPlatform;
    }


    public function qr(String $cedula)
    {
        return redirect()->to('connectliveness/'.$cedula);
    }
    
    
}