<?php

namespace App\Http\Controllers;

use App\Models\Alfa;
use App\Models\LogFacialEnvivoUnoAUno;
use App\Http\Requests\StoreAlfaRequest;
use App\Http\Requests\UpdateAlfaRequest;
use App\Models\LogMasiva;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;


use Exception;

use SoapClient;
use SoapFault;
use App\Jobs\ConsumeMatcher;

class AlfaController extends Controller
{

    protected $client;
    protected $fileName;

    protected $usuario;
    protected $idLogMasiva;

    /**
     * Display a listing of the resource.
     */
    public function importaralfa()
    {
        $timeIni = $this->microtime_float();
        $path = storage_path('app/input/alfa.csv');

        if (!file_exists($path) || !is_readable($path)) {
            return response()->json(['error' => 'El archivo CSV no existe o no se puede leer.'], 400);
        }

        $header = null;
        $data = [];
        $dataError = [];

        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        $i = 0;

        $validations = [
            'pin' => ['msj' => 'debe ser un número y debe tener entre 7 y 10 digitos.', 'expreg' => '/^\d{1,14}$/'],
            'nombre1' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'nombre2' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'partícula' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'apellido1' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'apellido2' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'explugar' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'expfecha' => ['msj' => 'debe ser una fecha valida, formato, YYYY-MM-DD', 'expreg' => '/^(\d{4})-(\d{2})-(\d{2})$/'],
            'vigencia' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
        ];



        foreach ($data as $row) {
            $i++;

            //Todos los string maximo 200 caracteres
            $dataInsert = [
                //Solo numeros, minimo 7, maximo 10
                'pin' => $row['PIN'],
                //Escapar apostrofes
                'nombre1' => $row['Nombre1'],
                //Escapar apostrofes
                'nombre2' => $row['Nombre2'],
                //Escapar apostrofes
                'partícula' => $row['Partícula'],
                //Escapar apostrofes
                'apellido1' => $row['Apellido1'],
                //Escapar apostrofes
                'apellido2' => $row['Apellido2'],
                //Escapar apostrofes
                'explugar' => $row['ExpLugar'],

                //Formato Fecha
                'expfecha' => $row['ExpFecha'],
                'vigencia' => $row['Vigencia'],
            ];

            $errors = [];


            foreach ($dataInsert as $key => $value) {
                if (isset($validations[$key]['expreg']) && !preg_match($validations[$key]['expreg'], $value)) {
                    $errors[$key] = "El campo ".$key." ".$validations[$key]['msj'];
                }
            }


            $dataTmp = $dataInsert;


            if( empty($errors) ){
                if (!Alfa::where('pin', $row['PIN'])->exists()) {
                    $pin = Alfa::create($dataInsert);
                    if(empty($pin)){
                        $dataTmp['error']  = "Error al insertar";
                    }
                    //echo "<pre>".print_r($pin , true)."</pre>";
                }
                else{
                    $dataTmp['error']  = "PIN ya existente";
                }
            }
            else{
                $dataTmp['error']  = implode("\r", $errors);
            }


            if(!empty($dataTmp['error'])){
                $dataError[$i]  = $dataTmp;
            }

        }
        /*echo "<pre>".print_r($dataError , true)."</pre>";
        die();*/
        $timeFin = $this->microtime_float();

        $tiempoTotal = number_format(($timeFin - $timeIni) , 2, '.', '');
        return view('alfa/guardadoFormulario', [
            "data" => $dataError,
            "timeFin" => $this->formatTime($timeFin),
            "timeIni" => $this->formatTime($timeIni),
            "tiempoTotal" => $tiempoTotal
        ]);

    }



    public function masiva()
    {
        // Obtener todos los registros de la tabla Alfa
        $registros = Alfa::all();

        // Iterar sobre los registros

        $logMasivaData = [
            'fechainicio' => Carbon::now(),
            'fechafin' => Carbon::now(),
            'usuariocarga_id' => 1,
            'totalregistros' => 0,
            'errortotalregistros' => 0,
        ];

        // Insertar los datos usando Eloquent
        $logMasiva = LogMasiva::create($logMasivaData);

        $index = 0;
        $usuario = Auth::user();
        $resultados = [];
        //var_dump($usuario);
        foreach ($registros as $registro) {
            // Realizar cualquier transformación o procesamiento necesario
            // Por ejemplo, transformar los datos para la tabla log_facial_envivo_uno_a_uno
            $logData = [
                'nut' => $registro->pin, // Ejemplo de asignación, ajusta según sea necesario
                'nuip' => $registro->pin, // Ejemplo de asignación, ajusta según sea necesario
                'resultado' => 'exitoso', // Ejemplo de valor estático, ajusta según sea necesario
                'fechafin' => Carbon::now(), // Usar la fecha actual
                'idusuario' => $usuario->id, // ID del usuario actual o cualquier otro valor
                'hashalgo' => '123hashito', // Ejemplo de cálculo hash
                'idmasiva' => $logMasiva->id,
            ];

            // Insertar en la tabla log_facial_envivo_uno_a_uno
            LogFacialEnvivoUnoAUno::create($logData);
            $logData['usuarioNombre'] = $usuario->name;
            $resultados[$index] = (object)$logData;
            $index++;
        }

        $logMasivaData['fechafin'] = Carbon::now();
        $logMasivaData['totalregistros'] = $index;


        $logMasiva->update($logMasivaData);


        // Devolver una vista, redirigir o devolver una respuesta JSON
        //return response()->json(['message' => 'Inserción completada'], 200);
        return view('alfa.masiva', [
            "resultados" => $resultados,
            "logMasiva" => $logMasivaData
        ]);
    }

    public function masivaTest()
    {
        try{
            $usuario = Auth::user();
            // Insertar los datos usando Eloquent
            $logMasivaData = [
                'fechainicio' => Carbon::now(),
                'fechafin' => Carbon::now(),
                'usuariocarga_id' => $usuario->id,
                'totalregistros' => 0,
                'errortotalregistros' => 0,
            ];

            $logMasiva = LogMasiva::create($logMasivaData);
            $idLogMasiva = $logMasiva->id;

            $fotosPath = "FotosMasiva";
            $directoryFotosPath = storage_path($fotosPath);
            if (!Storage::exists($directoryFotosPath)) {
                Storage::makeDirectory($directoryFotosPath);
            }
            $directoryFotosPath = storage_path("app/".$fotosPath);

            //$files = File::allFiles($directoryFotosPath);
            $directories = File::directories($directoryFotosPath);
            // Mostrar los nombres de los archivos
            $iFile = 0;

            $logMasivaData['fechafin'] = Carbon::now();
            $logMasivaData['totalregistros'] = count($directories);

            $logMasiva->update($logMasivaData);
            /*$apiUrl = 'http://localhost:3000/location';
            
            // Parámetros de la API
            $params = [];

            // Realizar la solicitud GET
            $response = Http::get($apiUrl, $params);

            // Verificar si la solicitud fue exitosa
            if ($response->successful()) {
                // Devolver la respuesta de la API
                $coordenadasResponse = (array)$response->json();
                var_dump($coordenadasResponse);
            } else {
                // Manejar el error
                //var_dump(['error' => 'Error al obtener el clima'], $response->status());
            }*/
            $coordenadasResponse =  $this->getCoordenadas();
            $coordenadasResponse['latitude'] = $coordenadasResponse["latitud"];
            $coordenadasResponse['longitude'] = $coordenadasResponse["longitud"];
            //echo "<pre>".print_r($coordenadasResponse , true)."</pre>";

        
            $i=0;
            foreach ($directories as $dir) {
                $files = File::files($dir);
                foreach ($files as $file) {
                    //echo "<pre>".print_r($file , true)."</pre>";
                    $fileName = $file->getFilename();
                    $msn = ConsumeMatcher::dispatch(
                        $fileName,
                        $usuario,
                        $idLogMasiva,
                        $coordenadasResponse
                    )->onQueue('photos');
                }  
                $i++;
                /*if($i > 1){
                    die();
                }*/
            }

        
        } catch (Exception $e) {
            // Registrar el error en los logs
            Log::error('Error en ConsumeMatcher: ' . $e->getMessage(), [
                'exception' => $e,
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            // Lanzar nuevamente la excepción para que el job falle
            throw $e;
        }

        return redirect('loader/' . $idLogMasiva);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos = Alfa::get();

        $logData = [
            'fechainicio' => Carbon::now(),
            'fechafin' => Carbon::now(),
            'usuariocarga_id' => 1,
            'totalregistros' => 0,
            'errortotalregistros' => 0,
        ];

        return view('alfa/resultadoCargaMasiva', compact('datos', 'logData'));
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
    public function store(StoreAlfaRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Alfa $alfa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alfa $alfa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlfaRequest $request, Alfa $alfa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alfa $alfa)
    {
        //
    }



    private function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }


    private function formatTime($timestamp)
    {
        $seconds = floor($timestamp);
        $microseconds = ($timestamp - $seconds) * 1e6;

        $carbon = Carbon::createFromTimestamp($seconds);
        $formattedTime = $carbon->format('Y/m/d H:i:s');

        return $formattedTime;
    }


        /**
     * Show the form for creating a new resource.
     */


    public function loaderAjax(Int $idmasiva)
    {
        // Realizar la consulta para contar los registros
        $total = LogMasiva::select('totalregistros')->where('id', $idmasiva)->first();
        $registros = LogFacialEnvivoUnoAUno::where('idmasiva', $idmasiva)->count();
        $mensaje = '';
        // Manejar el caso cuando no se encuentran registros
        if (empty($total)) {
            $mensaje = 'Registro no encontrado';
        }

        return response()->json( [
            "total" => $total->totalregistros,
            "registros" => $registros,
            "mensaje" => $mensaje,
            "idmasiva" => $idmasiva,
        ] , 200);     
    }



    public function loader(Int $idmasiva)
    {
        // Realizar la consulta para contar los registros
        $total = LogMasiva::select('totalregistros')->where('id', $idmasiva)->first();
        $registros = LogFacialEnvivoUnoAUno::where('idmasiva', $idmasiva)->count();
        $mensaje = '';
        // Manejar el caso cuando no se encuentran registros
        if (empty($total)) {
            $mensaje = 'Registro no encontrado';
        }

        return view('loader', [
            "total" => $total->totalregistros,
            "registros" => $registros,
            "mensaje" => $mensaje,
            "idmasiva" => $idmasiva

        ]);     

    }

    private function  getCoordenadas(){
        $capitalesColombia = [
            ['latitud' => 4.7110, 'longitud' => -74.0721],  // Bogotá
            ['latitud' => 6.2442, 'longitud' => -75.5812],  // Medellín
            ['latitud' => 3.4516, 'longitud' => -76.5319],  // Cali
            ['latitud' => 10.9685, 'longitud' => -74.7813], // Barranquilla
            ['latitud' => 10.3910, 'longitud' => -75.4794], // Cartagena
            ['latitud' => 7.8939, 'longitud' => -72.5078],  // Cúcuta
            ['latitud' => 7.1193, 'longitud' => -73.1227],  // Bucaramanga
            ['latitud' => 4.8133, 'longitud' => -75.6961],  // Pereira
            ['latitud' => 11.2408, 'longitud' => -74.1990], // Santa Marta
            ['latitud' => 4.4389, 'longitud' => -75.2322],  // Ibagué
            ['latitud' => 4.1420, 'longitud' => -73.6266],  // Villavicencio
            ['latitud' => 1.2136, 'longitud' => -77.2811],  // Pasto
            ['latitud' => 5.0703, 'longitud' => -75.5138],  // Manizales
            ['latitud' => 8.7472, 'longitud' => -75.8814],  // Montería
            ['latitud' => 2.9386, 'longitud' => -75.2678],  // Neiva
            ['latitud' => 4.5339, 'longitud' => -75.6811],  // Armenia
            ['latitud' => 9.3047, 'longitud' => -75.3978],  // Sincelejo
            ['latitud' => 11.5444, 'longitud' => -72.9078], // Riohacha
            ['latitud' => 5.5353, 'longitud' => -73.3678],  // Tunja
            ['latitud' => 1.6144, 'longitud' => -75.6062],  // Florencia
            ['latitud' => 5.6947, 'longitud' => -76.6611],  // Quibdó
            ['latitud' => 2.4448, 'longitud' => -76.6147],  // Popayán
            ['latitud' => 10.4631, 'longitud' => -73.2532], // Valledupar
            ['latitud' => 1.1476, 'longitud' => -76.6475],  // Mocoa
            ['latitud' => 12.5833, 'longitud' => -81.7006], // San Andrés
            ['latitud' => -4.2153, 'longitud' => -69.9406], // Leticia
            ['latitud' => 5.3378, 'longitud' => -72.3959],  // Yopal
            ['latitud' => 3.8653, 'longitud' => -67.9239],  // Inírida
            ['latitud' => 6.1847, 'longitud' => -67.4851],  // Puerto Carreño
            ['latitud' => 1.1983, 'longitud' => -70.1739]   // Mitú
        ];
        $index = array_rand($capitalesColombia);
        return $capitalesColombia[$index];
        
    }
}
