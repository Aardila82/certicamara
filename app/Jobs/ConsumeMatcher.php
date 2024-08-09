<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Storage;
use Exception;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\LogFacialEnvivoUnoAUno;
use App\Models\ResponseMatcherMasiva;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;


class ConsumeMatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $client;
    protected $fileName;

    protected $usuario;
    protected $idLogMasiva;

    protected $coordenadasResponse;
    public static $startTimeKey = 'csv_processing_start_time';
    public static $endTimeKey = 'csv_processing_end_time';


    /**
     * Create a new job instance.
     */

    public function __construct(
        
        $fileName, 
        $usuario,
        $idLogMasiva,
        $coordenadasResponse
        )
    {
        $this->fileName = $fileName;
        $this->usuario = $usuario;
        $this->idLogMasiva = $idLogMasiva;
        $this->coordenadasResponse = $coordenadasResponse;

    }

    public function handle(){

            //echo $file->getFilename() . '<br>';
            $cedula = explode(".", $this->fileName)[0];
            $directoryFotosPath = storage_path('app/FotosMasiva/' . $cedula);
            echo $this->idLogMasiva;
            
            $foto = $directoryFotosPath . "/" . $this->fileName;

            try {
                $dtini = Carbon::now();
                $fechainicio = $dtini->format('Y-m-d H:i:s.u');
                
                $base64 = base64_encode(file_get_contents($foto));
                $sha256 = hash('SHA256' , $base64);
                $nextValue = DB::select('SELECT nextval(\'secuencia_facial\') as value');
                $nut = $nextValue[0]->value;
                echo 'Next value: ' . $nut;
                echo 'latitude: ' . $this->coordenadasResponse['latitude'];
                echo 'longitude: ' . $this->coordenadasResponse['longitude'];

                // Crear la solicitud
                $request =  [
                    'nut2' => $nut,
                    'oaid_id' => '1234567890',
                    'cliente_id' => '12345678',
                    'nuip_aplicante' => $cedula,
                    'dispositivo_id' => '12345678901234567890',
                    'latitud' => $this->coordenadasResponse['latitude'], 
                    'longitud' => $this->coordenadasResponse['longitude'],
                    'rostro2' => $base64,
                    'file_foto_sha256' => $sha256
                ];

                // Llamar al método SOAP

                $response = $this->callSoapService($request);
                var_dump($response);
                $dt = Carbon::now();
                // Insertar los datos usando Eloquent
                // Insertar en la tabla log_facial_envivo_uno_a_uno
                $logData = [
                    'nut' => $nut, // Ejemplo de asignación, ajusta según sea necesario
                    'nuip' => $cedula, // Ejemplo de asignación, ajusta según sea necesario
                    'resultado' => $response["resultado_cotejo"], // Ejemplo de valor estático, ajusta según sea necesario
                    'fechainicio' => $fechainicio, // Usar la fecha actual
                    'fechafin' => $dt->format('Y-m-d H:i:s.u'), // Usar la fecha actual

                    'idusuario' => $this->usuario->id, // ID del usuario actual o cualquier otro valor
                    //'hashalgo' => $sha256, // Ejemplo de cálculo hash
                    'hashalgo' => $sha256, // Ejemplo de cálculo hash
                    'idmasiva' => $this->idLogMasiva,
                    //'response' => json_encode($response),
                ];

            
                $logData['usuarioNombre'] = $this->usuario->name;
                $idUnoAUno = LogFacialEnvivoUnoAUno::create($logData);


                $response["idmasiva"] = (int)$this->idLogMasiva;
                $response["idunoauno"] =$idUnoAUno->id;
                $idResponse = ResponseMatcherMasiva::create($response);
                Log::info('Valor del array res: ' . $idResponse);


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
        
    }
    

    private function callSoapService($request)
    {
        //var_dump($request);
        echo $request['latitud'];
        // URL del servicio SOAP

        $client = new Client();

        // Define la URL del servicio SOAP
        $url = 'http://172.17.111.22:8834/wsrnec/ValidateCandidate/?name=arthuro';
        echo "<br>file_foto_sha256 : ".$request['file_foto_sha256']."<br>";
        // Define el cuerpo XML de la solicitud SOAP
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="ws.mockrnec.com" xmlns:apl="apl">
            <soapenv:Header/>
            <soapenv:Body>
                <ws:validate_client_data>
                    <apl:nut>'.$request['nut2'].'</apl:nut>
                    <apl:oaid_id>'.$request['oaid_id'].'</apl:oaid_id>
                    <apl:cliente_id>'.$request['cliente_id'].'</apl:cliente_id>
                    <apl:nuip_aplicante>'.$request['nuip_aplicante'].'</apl:nuip_aplicante>
                    <apl:dispositivo_id>'.$request['dispositivo_id'].'</apl:dispositivo_id>
                    <apl:coordenadas>
                        <apl:latitud>'.$request['latitud'].'</apl:latitud>
                        <apl:longitud>'.$request['longitud'].'</apl:longitud>
                    </apl:coordenadas>
                    <apl:rostro>'.$request['rostro2'].'</apl:rostro>
                    <apl:file_foto_sha256>'.$request['file_foto_sha256'].'</apl:file_foto_sha256>
                </ws:validate_client_data>
            </soapenv:Body>
        </soapenv:Envelope>';

        try {
            // Enviar la solicitud POST con el XML en el cuerpo
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'text/xml',
                    'Accept' => 'text/xml',
                ],
                'body' => $xml,
            ]);

            // Obtener la respuesta del servicio
            $responseBody = $response->getBody()->getContents();

            // Cargar el XML en un objeto SimpleXMLElement
            $xml = simplexml_load_string($responseBody);

            // Registrar los namespaces utilizados en el XML
            $namespaces = $xml->getNamespaces(true);

            // Navegar por el XML utilizando los namespaces
            $body = $xml->children($namespaces['soap11env'])->Body;

            $response = $body->children($namespaces['tns'])->validate_client_dataResponse;
            $properties = $response->children($namespaces['s1']);

            $res = [
                'codigo_resultado' => (string)$properties->codigoResultado,
                'nut' => (string)$properties->nut,
                'nuip' => (string)$properties->nuip,
                'id_log' => (string)$properties->idLog,
                'id_oaid' => (string)$properties->idOAID,
                'id_cliente' => (string)$properties->idCliente,
                'resultado_cotejo' => (string)$properties->resultado_cotejo,
                'primer_nombre' => (string)$properties->primerNombre,
                'segundo_nombre' => (string)$properties->segundoNombre,
                'codigo_particula' => (string)$properties->codigoParticula,
                'descripcion_particula' => (string)$properties->descripcionParticula,
                'primer_apellido' => (string)$properties->primerApellido,
                'segundo_apellido' => (string)$properties->segundoApellido,
                'lugar_expedicion' => (string)$properties->lugarExpedicion,
                'fecha_expedicion' => (string)$properties->fechaExpedicion,
                'codigo_vigencia' => (string)$properties->codigoVigencia,
                'descripcion_vigencia' => (string)$properties->descripcionVigencia,
                'message_error' => isset($properties->messageError) ? (string)$properties->messageError : '', // Este campo es opcional
            ];
            
            
            //Log::info('Valor del array properties: ' . json_encode((array)$properties));
            //Log::info('Valor del array res: ' . json_encode($res));
            //Log::info('Valor del array request: ' . json_encode($request));

            // Mostrar el valor de codigoResultado

            return $res;

        } catch (\Exception $e) {
            // Manejar excepciones
            return  $e->getMessage();
        }
    }
}
