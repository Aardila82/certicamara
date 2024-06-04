<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Seeder;

class DepartamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            ["codigodivipola" => "05", "nombre" => "Antioquia"],
            ["codigodivipola" => "08", "nombre" => "Atlántico"],
            ["codigodivipola" => "11", "nombre" => "Bogotá, D.C."],
            ["codigodivipola" => "13", "nombre" => "Bolívar"],
            ["codigodivipola" => "15", "nombre" => "Boyacá"],
            ["codigodivipola" => "17", "nombre" => "Caldas"],
            ["codigodivipola" => "18", "nombre" => "Caquetá"],
            ["codigodivipola" => "19", "nombre" => "Cauca"],
            ["codigodivipola" => "20", "nombre" => "Cesar"],
            ["codigodivipola" => "23", "nombre" => "Córdoba"],
            ["codigodivipola" => "25", "nombre" => "Cundinamarca"],
            ["codigodivipola" => "27", "nombre" => "Chocó"],
            ["codigodivipola" => "41", "nombre" => "Huila"],
            ["codigodivipola" => "44", "nombre" => "La Guajira"],
            ["codigodivipola" => "47", "nombre" => "Magdalena"],
            ["codigodivipola" => "50", "nombre" => "Meta"],
            ["codigodivipola" => "52", "nombre" => "Nariño"],
            ["codigodivipola" => "54", "nombre" => "Norte de Santander"],
            ["codigodivipola" => "63", "nombre" => "Quindío"],
            ["codigodivipola" => "66", "nombre" => "Risaralda"],
            ["codigodivipola" => "68", "nombre" => "Santander"],
            ["codigodivipola" => "70", "nombre" => "Sucre"],
            ["codigodivipola" => "73", "nombre" => "Tolima"],
            ["codigodivipola" => "76", "nombre" => "Valle del Cauca"],
            ["codigodivipola" => "81", "nombre" => "Arauca"],
            ["codigodivipola" => "85", "nombre" => "Casanare"],
            ["codigodivipola" => "86", "nombre" => "Putumayo"],
            ["codigodivipola" => "88", "nombre" => "Archipiélago de San Andrés, Providencia y Santa Catalina"],
            ["codigodivipola" => "91", "nombre" => "Amazonas"],
            ["codigodivipola" => "94", "nombre" => "Guainía"],
            ["codigodivipola" => "95", "nombre" => "Guaviare"],
            ["codigodivipola" => "97", "nombre" => "Vaupés"],
            ["codigodivipola" => "99", "nombre" => "Vichada"],
        ];

        foreach ($departamentos as $departamentoData) {
            $departamento = new Departamento();
            $departamento->codigodivipola = $departamentoData['codigodivipola'];
            $departamento->nombre = $departamentoData['nombre'];
            $departamento->estado = true; // Puedes establecer el estado según sea necesario
            $departamento->save();
        }
    }
}

