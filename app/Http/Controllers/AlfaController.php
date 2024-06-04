<?php

namespace App\Http\Controllers;

use App\Models\Alfa;
use App\Http\Requests\StoreAlfaRequest;
use App\Http\Requests\UpdateAlfaRequest;
use Illuminate\Support\Facades\DB;


class AlfaController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function importaralfa()
    {
        $timeIni = $this->microtime_float();
        $path = storage_path('app/input/alfa.csv');

        if (!file_exists($path) || !is_readable($path)) {
            return response()->json(['error' => 'El archivo CSV no existe o no se puede leer.'], 400);
        }

        $header = null;
        $data = [];
        $dataError = [];

        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        $i = 0;

        $validations = [
            'pin' => ['msj' => 'debe ser un número y debe tener entre 7 y 10 digitos.', 'expreg' => '/^\d{7,10}$/'],
            'nombre1' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'nombre2' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'partícula' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'apellido1' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'apellido2' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'explugar' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
            'expfecha' => ['msj' => 'debe ser una fecha valida, formato, YYYY-MM-DD', 'expreg' => '/^(\d{4})-(\d{2})-(\d{2})$/'],
            'vigencia' => ['msj' => 'debe tener menos de 200 caracteres', 'expreg' => '/^.{0,200}$/'],
        ];



        foreach ($data as $row) {
            $i++;

            //Todos los string maximo 200 caracteres
            $dataInsert = [
                //Solo numeros, minimo 7, maximo 10
                'pin' => $row['PIN'],
                //Escapar apostrofes
                'nombre1' => $row['Nombre1'],
                //Escapar apostrofes
                'nombre2' => $row['Nombre2'],
                //Escapar apostrofes
                'partícula' => $row['Partícula'],
                //Escapar apostrofes
                'apellido1' => $row['Apellido1'],
                //Escapar apostrofes
                'apellido2' => $row['Apellido2'],
                //Escapar apostrofes
                'explugar' => $row['ExpLugar'],

                //Formato Fecha
                'expfecha' => $row['ExpFecha'],
                'vigencia' => $row['Vigencia'],
            ];

            $errors = [];
         
            
            foreach ($dataInsert as $key => $value) {
                if (isset($validations[$key]['expreg']) && !preg_match($validations[$key]['expreg'], $value)) {
                    $errors[$key] = "El campo ".$key." ".$validations[$key]['msj'];
                }
            }


            $dataTmp = $dataInsert;
            

            if( empty($errors) ){
                if (!Alfa::where('pin', $row['PIN'])->exists()) {
                    $pin = Alfa::create($dataInsert);
                    if(empty($pin)){
                        $dataTmp['error']  = "Error al insertar";
                    }
                    //echo "<pre>".print_r($pin , true)."</pre>";
                }
                else{
                    $dataTmp['error']  = "PIN ya existente";
                }                
            }
            else{
                $dataTmp['error']  = implode("\r", $errors);
            }


            if(!empty($dataTmp['error'])){
                $dataError[$i]  = $dataTmp;
            }

        }
        /*echo "<pre>".print_r($dataError , true)."</pre>";
        die();*/
        $timeFin = $this->microtime_float();

        $tiempoTotal = number_format(($timeFin - $timeIni) , 2, '.', '');
        return view('alfa/guardadoFormulario', [
            "data" => $dataError,
            "tiempoTotal" => $tiempoTotal
        ]);

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreAlfaRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Alfa $alfa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alfa $alfa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlfaRequest $request, Alfa $alfa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alfa $alfa)
    {
        //
    }



    private function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}
