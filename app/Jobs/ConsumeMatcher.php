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
            $directoryFotosPath = storage_path('app/fotos');
            echo $this->idLogMasiva;

            //$cedula = str_replace(".txt", "", $this->fileName);
            $cedula = explode(".", $this->fileName)[0];
            $foto = $directoryFotosPath . "/" . $this->fileName;

            try {
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
                    'oaid_id' => 'OAID123',
                    'cliente_id' => 'CLT678',
                    'nuip_aplicante' => $cedula,
                    'dispositivo_id' => 'DISP789',
                    'latitud' => $this->coordenadasResponse['latitude'], 
                    'longitud' => $this->coordenadasResponse['longitude'],
                    'rostro2' => $base64,
                    'file_foto_sha256' => $sha256
                ];

                // Llamar al método SOAP

                $response = $this->callSoapService($request);

                // Insertar los datos usando Eloquent
                // Insertar en la tabla log_facial_envivo_uno_a_uno
                $logData = [
                    'nut' => $nut, // Ejemplo de asignación, ajusta según sea necesario
                    'nuip' => $cedula, // Ejemplo de asignación, ajusta según sea necesario
                    'resultado' => $response["resultado_cotejo"], // Ejemplo de valor estático, ajusta según sea necesario
                    'fechafin' => Carbon::now(), // Usar la fecha actual
                    'idusuario' => $this->usuario->id, // ID del usuario actual o cualquier otro valor
                    'hashalgo' => $sha256, // Ejemplo de cálculo hash
                    'idmasiva' => $this->idLogMasiva,
                ];

                LogFacialEnvivoUnoAUno::create($logData);
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
        
    }
    

    private function callSoapService($request)
    {
        //var_dump($request);
        echo $request['latitud'];
        // URL del servicio SOAP

        $client = new Client();

        // Define la URL del servicio SOAP
        $url = 'http://172.17.111.22:8834/wsrnec/ValidateCandidate/?name=arthuro';

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
                    <apl:file_foto_sha256>e32b061fa28a3cdbd846421de142629a60d69c8b24f7a0372bae71546829cf32</apl:file_foto_sha256>
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

            $res = array(
                'codigoResultado' => (string)$properties->codigoResultado,
                'nut' => (string)$properties->nut,
                'nuip' => (string)$properties->nuip,
                'idLog' => (string)$properties->idLog,
                'idOAID' => (string)$properties->idOAID,
                'idCliente' => (string)$properties->idCliente,
                'resultado_cotejo' => (string)$properties->resultado_cotejo,
            );

            // Mostrar el valor de codigoResultado

            return $res;

        } catch (\Exception $e) {
            // Manejar excepciones
            return  $e->getMessage();
        }
    }
}
