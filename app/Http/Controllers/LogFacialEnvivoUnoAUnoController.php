<?php

namespace App\Http\Controllers;

use App\Models\LogFacialEnvivoUnoAUno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Exception;

class LogFacialEnvivoUnoAUnoController extends Controller
{

    public function lista($id)
    {
        // Cargamos los registros junto con la relación usuario
        //$logs = LogFacialEnvivoUnoAUno::with('usuario')->get();

        $results = DB::table('log_facial_envivo_uno_a_uno')
            ->join('usuarios', 'log_facial_envivo_uno_a_uno.idusuario', '=', 'usuarios.id')
            ->join('response_matcher_masiva', 'response_matcher_masiva.idunoauno', '=', 'log_facial_envivo_uno_a_uno.id')

            ->select(
                'log_facial_envivo_uno_a_uno.id',
                'log_facial_envivo_uno_a_uno.nut',
                'log_facial_envivo_uno_a_uno.nuip',
                'log_facial_envivo_uno_a_uno.resultado',
                'log_facial_envivo_uno_a_uno.fechafin',
                'usuarios.nombre1',
                'usuarios.nombre2',
                'usuarios.apellido1',
                'usuarios.apellido2',
                'usuarios.numerodedocumento',

                'response_matcher_masiva.codigo_resultado',
                'response_matcher_masiva.nut as response_nut',
                'response_matcher_masiva.nuip as response_nuip',
                'response_matcher_masiva.id_log',
                'response_matcher_masiva.id_oaid',
                'response_matcher_masiva.id_cliente',
                'response_matcher_masiva.resultado_cotejo',
                'response_matcher_masiva.primer_nombre',
                'response_matcher_masiva.segundo_nombre',
                'response_matcher_masiva.codigo_particula',
                'response_matcher_masiva.descripcion_particula',
                'response_matcher_masiva.primer_apellido',
                'response_matcher_masiva.segundo_apellido',
                'response_matcher_masiva.lugar_expedicion',
                'response_matcher_masiva.fecha_expedicion',
                'response_matcher_masiva.codigo_vigencia',
                'response_matcher_masiva.descripcion_vigencia',
                'response_matcher_masiva.message_error',
                'response_matcher_masiva.idunoauno',
                'response_matcher_masiva.idmasiva'
            )
            ->where('log_facial_envivo_uno_a_uno.idmasiva', $id)
            ->get();


        // Pasamos los registros a la vista
        return view('log.facial', [
            'logs' => $results,
            'id' => $id
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = DB::table('log_facial_envivo_uno_a_uno')
            ->join('usuario', 'log_facial_envivo_uno_a_uno.idusuario', '=', 'usuario.id')
            ->join('alfas', 'log_facial_envivo_uno_a_uno.nuip', '=', 'alfas.pin')
            ->select(
                'log_facial_envivo_uno_a_uno.*',
                'usuario.nombre1',
                'usuario.nombre2',
                'usuario.apellido1',
                'usuario.apellido2',
                'usuario.numerodedocumento',
                'alfas.nombre1',
                'alfas.nombre2',
                'alfas.apellido1',
                'alfas.apellido2',

                )
            ->get();


        foreach ($results as $result) {
            echo $result->nombre1 . ' ' . $result->nombre2 . ' ' . $result->apellido1 . ' ' . $result->apellido2 . PHP_EOL;
        }
    }

    public function exportCsv($id)
    {
        $results = DB::table('log_facial_envivo_uno_a_uno')
            ->join('usuarios', 'log_facial_envivo_uno_a_uno.idusuario', '=', 'usuarios.id')
            ->join('response_matcher_masiva', 'response_matcher_masiva.idunoauno', '=', 'log_facial_envivo_uno_a_uno.id')

            ->select(
                'log_facial_envivo_uno_a_uno.id',
                'log_facial_envivo_uno_a_uno.nut',
                'log_facial_envivo_uno_a_uno.nuip',
                'log_facial_envivo_uno_a_uno.resultado',
                'log_facial_envivo_uno_a_uno.fechafin',
                'usuarios.nombre1',
                'usuarios.nombre2',
                'usuarios.apellido1',
                'usuarios.apellido2',
                'usuarios.numerodedocumento',

                'response_matcher_masiva.codigo_resultado',
                'response_matcher_masiva.nut as response_nut',
                'response_matcher_masiva.nuip as response_nuip',
                'response_matcher_masiva.id_log',
                'response_matcher_masiva.id_oaid',
                'response_matcher_masiva.id_cliente',
                'response_matcher_masiva.resultado_cotejo',
                'response_matcher_masiva.primer_nombre',
                'response_matcher_masiva.segundo_nombre',
                'response_matcher_masiva.codigo_particula',
                'response_matcher_masiva.descripcion_particula',
                'response_matcher_masiva.primer_apellido',
                'response_matcher_masiva.segundo_apellido',
                'response_matcher_masiva.lugar_expedicion',
                'response_matcher_masiva.fecha_expedicion',
                'response_matcher_masiva.codigo_vigencia',
                'response_matcher_masiva.descripcion_vigencia',
                'response_matcher_masiva.message_error',
                'response_matcher_masiva.idunoauno',
                'response_matcher_masiva.idmasiva'
            )
            ->where('log_facial_envivo_uno_a_uno.idmasiva', $id)
            ->get();

        $filename = "logs_facial.csv";

        return response()->streamDownload(function() use ($results) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID',
                'NUT',
                'NUIP',
                'Resultado',
                'Fecha Fin',
                'Nombre1',
                'Nombre2',
                'Apellido1',
                'Apellido2',
                'Numero de Documento'
            ]);

            foreach ($results as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->nut,
                    $row->nuip,
                    $row->resultado,
                    $row->fechafin,
                    $row->nombre1,
                    $row->nombre2,
                    $row->apellido1,
                    $row->apellido2,
                    $row->numerodedocumento
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }


    public function listaunoauno()
{
    $posts = DB::table('log_facial_envivo_uno_a_uno')
                ->select('id', 'nut', 'nuip', 'resultado', 'fechafin', 'idusuario', 'hashalgo', 'idmasiva', 'created_at', 'updated_at')
                ->whereNotNull('idmasiva')
                ->where('idmasiva', '=', 0)
                ->get();

    return view('log.unoauno', compact('posts'));
}

public function exportCsv2()
{
    $posts = DB::table('log_facial_envivo_uno_a_uno')
                ->select('id', 'nut', 'nuip', 'resultado', 'fechafin', 'idusuario', 'hashalgo', 'idmasiva', 'created_at', 'updated_at')
                ->where('idmasiva', '>', 0)
                ->get();

    $csvExporter = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
    $csvExporter->insertOne(['ID', 'NUT', 'NUIP', 'Resultado', 'Fecha Fin', 'ID Usuario', 'Hash', 'ID Masiva', 'Creado', 'Actualizado']);

    foreach ($posts as $post) {
        $csvExporter->insertOne(get_object_vars($post));
    }

    $csvContent = $csvExporter->getContent();

    $csvFileName = 'posts.csv';
    $zipFileName = 'posts.zip';

    $tempFile = tempnam(sys_get_temp_dir(), $csvFileName);
    file_put_contents($tempFile, $csvContent);

    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
        $zip->addFile($tempFile, $csvFileName);
        $zip->close();
    }

    unlink($tempFile);

    return response()->download($zipFileName)->deleteFileAfterSend(true);
}

public function executeJar()
{
    // Ruta al archivo .jar en el directorio storage
    $jarFilePath = storage_path('app/libs/commons4j-3.0.4.jar');

    // Comando para ejecutar el archivo .jar
    $command = "java -jar $jarFilePath 2>&1"; // Redirige stderr a stdout

    // Ejecutar el comando
    $output = [];
    $returnVar = 0;
    exec($command, $output, $returnVar);

    // Registrar la salida y el código de retorno para depuración
    Log::info('Comando ejecutado: ' . $command);
    Log::info('Salida del comando: ' . implode("\n", $output));
    Log::info('Código de retorno: ' . $returnVar);

    // Revisar el resultado de la ejecución
    if ($returnVar === 0) {
        // Éxito
        return response()->json([
            'message' => 'Archivo .jar ejecutado exitosamente',
            'output' => $output,
        ]);
    } else {
        // Error
        return response()->json([
            'message' => 'Error al ejecutar el archivo .jar',
            'output' => $output,
            'error' => $returnVar,
        ], 500);
    }
}


public function upload(Request $request)
    {
        $request->validate([
            'image' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!base64_decode($value, true)) {
                    $fail('The ' . $attribute . ' is not a valid base64 encoded string.');
                }
            }],
        ]);

        $base64Image = $request->input('image');
        $imageData = base64_decode($base64Image);

        // Guardar la imagen en el sistema de archivos
        $imageName = time() . '.png';
        Storage::disk('public')->put($imageName, $imageData);

        return response()->json(['message' => 'Image uploaded successfully', 'image_name' => $imageName]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }
}
