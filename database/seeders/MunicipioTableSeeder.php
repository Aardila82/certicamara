<?php

namespace Database\Seeders;

use App\Models\Municipio;
use App\Models\Departamento;
use Illuminate\Database\Seeder;

class MunicipioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ruta al archivo CSV
        $filePath = storage_path('app/municipios.csv');

        // Abrir el archivo CSV
        $file = fopen($filePath, 'r');

        // Leer la primera fila (encabezados) y descartarla
        fgetcsv($file);

        // Iterar sobre las filas del archivo CSV
        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            // Verificar que la fila tiene los campos necesarios
            if (count($data) < 3) {
                continue; // Saltar la fila si no tiene suficientes campos
            }

            // Obtener el ID del departamento basado en el código DIVIPOLA
            $departamento = Departamento::where('codigodivipola', trim($data[1]))->first();

            // Si no se encuentra el departamento, saltar la fila
            if (!$departamento) {
                continue;
            }

            // Crear un nuevo municipio y asignar los valores del CSV
            $municipio = new Municipio();
            $municipio->nombre = $data[0];
            $municipio->departamento_id = $departamento->id;
            $municipio->codigodivipola = $data[1];
            $municipio->numeromunicipio = $data[2];
            $municipio->estado = true; // Puedes establecer el estado según sea necesario
            $municipio->save();
        }

        // Cerrar el archivo
        fclose($file);
    }
}


