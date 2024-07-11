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

        $directoryMuniciasPath = 'minucias';
        if (!Storage::exists($directoryMuniciasPath)) {
            Storage::makeDirectory($directoryMuniciasPath);
        }
        $directoryMuniciasPath = storage_path("app/".$directoryMuniciasPath);


        $directoryFotosPath = storage_path('fotos');
        if (!Storage::exists($directoryFotosPath)) {
            Storage::makeDirectory($directoryFotosPath);
        }
        $directoryFotosPath = storage_path("app/".$directoryFotosPath);


        $files = File::allFiles($directoryMuniciasPath);

        // Mostrar los nombres de los archivos
        $iFile = 0;

        $logMasivaData['fechafin'] = Carbon::now();
        $logMasivaData['totalregistros'] = count($files);

        $logMasiva->update($logMasivaData);

        foreach ($files as $file) {
            $fileName = $file->getFilename();
            /*$msn = ConsumeMatcher::dispatch(
                $fileName,
                $usuario,
                $idLogMasiva
            )->onQueue('photos');*/

            //echo $file->getFilename() . '<br>';

            $this->fileName = $fileName;
            $this->usuario = $usuario;
            $this->idLogMasiva = $idLogMasiva;


            $directoryFotosPath = storage_path('app/fotos');

            $cedula = str_replace(".txt", "", $this->fileName);
            $foto = $directoryFotosPath . "/" . $cedula . ".jpg";
            $sha256 = hash('SHA256' , $foto);

            try {
                // Crear la solicitud
                $request =  [
                    'nut2' => '12345',
                    'oaid_id' => 'OAID123',
                    'cliente_id' => 'CLT678',
                    'nuip_aplicante' => $cedula,
                    'dispositivo_id' => 'DISP789',
                    'coordenadas' => ['latitud' => '12.345678', 'longitud' => '98.765432'],
                    'rostro2' => 'encoded_face_data',
                    'file_foto_sha256' => 'sha256hash'
                ];

                $options = [
                    'trace' => 4,
                    'exceptions' => true
                ];
                // Llamar al método SOAP
                /*$client = new SoapClient('http://localhost/mock_wsdl.wsdl', $options);

                $response = $client->validate_client_data(['validate_client_data' => $request]);
                // Insertar los datos usando Eloquent
                // Insertar en la tabla log_facial_envivo_uno_a_uno
                $logData = [
                    'nut' => $cedula, // Ejemplo de asignación, ajusta según sea necesario
                    'nuip' => $cedula, // Ejemplo de asignación, ajusta según sea necesario
                    'resultado' => $response->resultado_cotejo, // Ejemplo de valor estático, ajusta según sea necesario
                    'fechafin' => Carbon::now(), // Usar la fecha actual
                    'idusuario' => $this->usuario->id, // ID del usuario actual o cualquier otro valor
                    'hashalgo' => $sha256, // Ejemplo de cálculo hash
                    'idmasiva' => $this->idLogMasiva,
                ];

                LogFacialEnvivoUnoAUno::create($logData);*/
                $logData['usuarioNombre'] = $this->usuario->name;
                //$resultados[$index] = (object)$logData;
                //$index++;


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
            //$iFile++;
            /*if($iFile > 2){
                die("muerto");
            }*/
            $iFile++;
            if($iFile > 5){
                die("Muerto");
            }
        }

        /*return view('loader', [
            "resultados" => $resultados,
            "logMasiva" => $logMasivaData
        ]);*/

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
            "mensaje" => $mensaje
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
            "mensaje" => $mensaje
        ]);     

    }
}
