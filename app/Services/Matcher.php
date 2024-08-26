<?php
    namespace App\Services;
    use Illuminate\Support\Facades\Log;
    use Carbon\Carbon;
    use App\Models\LogFacialEnvivoUnoAUno;
    use App\Models\ResponseMatcherMasiva;
    
    use Illuminate\Support\Facades\DB;
    use GuzzleHttp\Client;
    use Illuminate\Support\Facades\Auth;
    use Exception;


    class Matcher{

        private $urlMatcher = 'http://172.17.111.22:8834/wsrnec/ValidateCandidate/?name=arthuro';
        private $usuario;
        
        public function __construct(){
            $this->usuario = Auth::user();
        }

        public function connect($cedula, $idLogMasiva, $base64, $sha256 , $latitude , $longitude, $atdpRuta){
            
            try {
                $nextValue = DB::select('SELECT nextval(\'secuencia_facial\') as value');
                $nut = $nextValue[0]->value;
                // Crear la solicitud
                $request =  [
                    'nut2' => $nut,
                    'oaid_id' => '1234567890',
                    'cliente_id' => '12345678',
                    'nuip_aplicante' => $cedula,
                    'dispositivo_id' => '12345678901234567890',
                    'latitud' => $latitude, 
                    'longitud' => $longitude,
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
                    'hash256' => $sha256, // Ejemplo de cálculo hash
                    'idmasiva' => $idLogMasiva,
                    'atdpruta' => $atdpRuta,
                    'aprobacion_atdp' => 1,
                    //'response' => json_encode($response),
                ];

                $logData['usuarioNombre'] = $this->usuario->name;
                $idUnoAUno = LogFacialEnvivoUnoAUno::create($logData);

                $response["idmasiva"] = (int)$idLogMasiva;
                $response["idunoauno"] = $idUnoAUno->id;
                $idResponse = ResponseMatcherMasiva::create($response);
                Log::info('Valor del array res: ' . $idResponse);


                //$resultados[$index] = (object)$logData;
                //$index++;

                return $response;
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

            // URL del servicio SOAP
    
            $client = new Client();
    
            // Define la URL del servicio SOAP
            
            //echo "<br>file_foto_sha256 : ".$request['file_foto_sha256']."<br>";
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
                $response = $client->request('POST', $this->urlMatcher, [
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