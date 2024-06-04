<?php

namespace Database\Seeders;
use App\Models\Roles;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class rolesTableSeeder  extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ["id" => "1", "nombre" => "SuperAdmin", "descripcion" => "SuperAdmin", "estado" => true],
            ["id" => "2", "nombre" => "Admin", "descripcion" => "Admin", "estado" => true],
            ["id" => "3", "nombre" => "Operario", "descripcion" => "Operario", "estado" => true],
        ];


        foreach ($roles as $rolData) {
            $roles = new roles();
            $roles->id = $rolData['id'];
            $roles->nombre = $rolData['nombre'];
            $roles->descripcion = $rolData['descripcion'];
            $roles->estado = $rolData['estado'];; 

            $roles->created_at =  date("Y-m-d H:i:s");
            $roles->updated_at =  date("Y-m-d H:i:s");

            $roles->save();

        }
    }
}
