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
use SoapFault;
use SoapClient;


class ConsumeMatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $client;
    protected $fileName;

    protected $usuario;
    protected $idLogMasiva;

    public static $startTimeKey = 'csv_processing_start_time';
    public static $endTimeKey = 'csv_processing_end_time';


    /**
     * Create a new job instance.
     */

    public function __construct(
        
        $fileName, 
        $usuario,
        $idLogMasiva
        )
    {
        $this->fileName = $fileName;
        $this->usuario = $usuario;
        $this->idLogMasiva = $idLogMasiva;
    }

    public function handle(){
            //echo $file->getFilename() . '<br>';
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
                $client = new SoapClient('http://localhost/mock_wsdl.wsdl', $options);

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
    

}
