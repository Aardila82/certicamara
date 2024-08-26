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
use ZipArchive;


use Illuminate\Support\Facades\Http;


class LogController extends Controller
{

    private $usuario;
        
    public function __construct(){
        $this->usuario = Auth::user();
    }


    public function zipExportAll()
    {
        //og CotejoIndividual
        $results = LogFotografia::all();
        $filename = "fotoscotejo.txt";
        
        $path = storage_path("app/logs/$filename");
        $handle = fopen($path, 'w');
        $header = [
            'NUT',
            'NUIP criterio de busqueda',
            'Peso en Kb',
            'Hash SHA256',
            'Fotografia',
        ];
        fwrite($handle, implode(';', $header) . "\n");

        foreach ($results as $row) {
            $array = [
                $row->nut,
                $row->nuip,
                $row->peso_real,
                $row->hash,
                $row->fotografia,
            ];
            fwrite($handle, implode(';', $array) . "\n");
        }

        // Cierra el archivo en el servidor
        fclose($handle);
        $files[] = $path; 
      

        //Log Liveness
        $results = LogLiveness::all();
        $filename = "liveness.txt";

        $path = storage_path("app/logs/$filename");
        $handle = fopen($path, 'w');

        $header = [
            'NUT',
            'ID de LIVENESS',
            'NUIP del APLICANTE',    
            'Fecha y hora del proceso de liveness',

            'Clase de liveness',
            'Resultado del liveness detection',
        ];
        fwrite($handle, implode(';', $header) . "\n");

        foreach ($results as $row) {
            $fecha = Carbon::parse($row->fecha);

            $fecha = substr($fecha->format('Y-m-d H:i:s.u'), 0, -3);

            $array = [
                $row->nut,
                $row->id,
                $row->nuip,

                $fecha,
                $row->clase,
                $row->resultadoLiveness,
            ];
            fwrite($handle, implode(';', $array) . "\n");
        }

        fclose($handle);
        $files[] = $path; 



        //Log Masivo
        $results = DB::table('log_facial_envivo_uno_a_uno')
        ->join('usuarios', 'log_facial_envivo_uno_a_uno.idusuario', '=', 'usuarios.id')
        ->join('response_matcher_masiva', 'response_matcher_masiva.idunoauno', '=', 'log_facial_envivo_uno_a_uno.id')

        ->select(
            'log_facial_envivo_uno_a_uno.id',
            'log_facial_envivo_uno_a_uno.nut',
            'log_facial_envivo_uno_a_uno.nuip',
            'log_facial_envivo_uno_a_uno.resultado',
            'log_facial_envivo_uno_a_uno.fechafin',
            'log_facial_envivo_uno_a_uno.fechainicio'
        )
        ->where('log_facial_envivo_uno_a_uno.idmasiva', '>', 0)
        ->get();
        
        $filename = "desempeno_facial.txt";
        $path = storage_path("app/logs/$filename");
        $handle = fopen($path, 'w');

        $header = [
            'NUIP',
            'Resultado del cotejo',
            'Score de cotejo',

            'Fecha Inicio de Cotejo',
            'Fecha Fin de Cotejo',
            'Tiempo de respuesta en milisegundos',
        ];
        fwrite($handle, implode(';', $header) . "\n");

        foreach ($results as $row) {

            $fechainicio = Carbon::parse($row->fechainicio);
            $fechafin = Carbon::parse($row->fechafin);
            $diferenciaEnMilisegundos = $fechainicio->diffInMilliseconds($fechafin);
            $fechainicio = substr($fechainicio->format('Y-m-d H:i:s.u'), 0, -3);
            $fechafin = substr($fechafin->format('Y-m-d H:i:s.u'), 0, -3);
            $diferenciaRedondeada = round($diferenciaEnMilisegundos);
            $resultado = strtoupper($row->resultado);
            $resultado = $resultado == "NOHIT" ? "NO-HIT" : $resultado;
            $array = [
                $row->nuip,
                strtoupper($resultado),
                '50',

                $fechainicio,
                $fechafin,
                $diferenciaRedondeada,
            ];
            fwrite($handle, implode(';', $array) . "\n");
        }

        fclose($handle);
        $files[] = $path; 


        //Log Un a uno
        $results = DB::table('log_facial_envivo_uno_a_uno')
            ->select('id', 'nut', 'nuip', 'resultado', 'fechafin', 'idusuario', 'hash256', 'idmasiva', 'created_at', 'updated_at')
            ->where('idmasiva', '=', 0)
            ->where('aprobacion_atdp', '=', true)
            ->get();

        $filename = "consultaenvivo.txt";

        $path = storage_path("app/logs/$filename");
        $handle = fopen($path, 'w');

        $header = [
            'NUT',
            'NUIP criterio de busqueda',
            'Resultado del cotejo',

            'Fecha Fin de Cotejo',
            'Usuario Origina Cotejo',
            'Hash SHA-256',
        ];

        fwrite($handle, implode(';', $header) . "\n");


        foreach ($results as $row) {

            $fechafin = Carbon::parse($row->fechafin);
            $fechafin = substr($fechafin->format('Y-m-d H:i:s:u'), 0, -3);
            $array = [
                $row->nut,
                $row->nuip,
                strtoupper($row->resultado),

                $fechainicio,
                $fechafin,
                $row->idusuario,
                $row->hash256,
            ];
            fwrite($handle, implode(';', $array) . "\n");
        }

        fclose($handle);
        $files[] = $path; 

        //Generacion Zip
        $zipFileName = 'logs.zip';
        $zipFilePath = storage_path("app/$zipFileName");

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $folderPath = storage_path('app/img11'); // Ruta de la carpeta 'img11'

            $folderPath = realpath($folderPath);
            $folderName = basename($folderPath);
    
            // Obtener todos los archivos en la carpeta
            $files = scandir($folderPath);
    
            foreach ($files as $file) {
                // Omitir los directorios '.' y '..'
                if ($file !== '.' && $file !== '..') {
                    $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
                    $relativePath = $folderName . '/' . $file;
    
                    // Añadir el archivo al ZIP
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);

    }





    
}