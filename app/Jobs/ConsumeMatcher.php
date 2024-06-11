<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ConsumeMatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $row;
    protected $totalRows;
    public static $startTimeKey = 'csv_processing_start_time';
    public static $endTimeKey = 'csv_processing_end_time';


    /**
     * Create a new job instance.
     */

    public function __construct($row, $totalRows)
    {
       // $this->user = $user;
       $this->row = $row;
       $this->totalRows = $totalRows;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Registrar la marca de tiempo de inicio
        if (!Cache::has(self::$startTimeKey)) {
            Cache::put(self::$startTimeKey, microtime(true), now()->addMinutes(30));
        }
        $msn = $this->processRow($this->row);
        // Registrar la marca de tiempo de fin al procesar la última fila
        if (Cache::increment('processed_rows_count') === $this->totalRows) {
            Cache::put(self::$endTimeKey, microtime(true), now()->addMinutes(30));

            // Calcular el tiempo total de procesamiento
            $startTime = Cache::get(self::$startTimeKey);
            $endTime = Cache::get(self::$endTimeKey);
            $executionTime = $endTime - $startTime;

            // Registrar el tiempo de ejecución
            Log::info("Procesamiento del CSV completado en {$executionTime} segundos.");
        }

        return $msn;
    }

    
    private function processRow($row) {
        $pin = $row['PIN'];
        $outputDir = 'fotos/';
    
        $msn = "";
        $sourcePath = 'persona.jpg';
        $destinationPath = 'fotos/'.$pin.'.jpg';

        // Ensure the destination directory exists
        if (!Storage::disk('local')->exists($outputDir)) {
            Storage::disk('local')->makeDirectory($outputDir);
            echo $msn =  "No la pudo crear.<br>";

        }

        // Use the copy method to copy the file
        if (Storage::disk('local')->exists($sourcePath)) {
            Storage::disk('local')->copy($sourcePath, $destinationPath);
            echo $msn = "Image copied successfully.<br>";
        } else {
            echo $msn =  "Source image not found.<br>";
        }
        return $msn;

    }


}
