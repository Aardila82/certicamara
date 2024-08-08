@extends('layouts.plantilla')

@section('titulo', 'Liveness')

@section('contenido')

    <style>
        .cell {
            border: 1px solid #d1e7dd; /* Borde azul claro */
            text-align: left;
        }
        .header-cell {
            background-color: #0d6efd; /* Fondo azul */
            color: white; /* Letra blanca */
            text-align: left;
            border: 1px solid white; /* Borde azul claro */

        }
    </style>

<div class="container-fluid text-center d-block mx-auto">
        <div class="loader-contenedor" id="loader-contenedor">
            <div class="loader"></div>
        </div>

        <h5>Respuesta de Matcher</h5>
        <div class="col-12" id="matcherResponse" >
            <div class="row">
                <div class="col-3"></div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Código Resultado</strong></div>
                        <div class="col-8 cell">{{ $data['codigo_resultado'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Nut</strong></div>
                        <div class="col-8 cell">{{ $data['nut'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Nuip</strong></div>
                        <div class="col-8 cell">{{ $data['nuip'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Id log</strong></div>
                        <div class="col-8 cell">{{ $data['id_log'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Id oaid</strong></div>
                        <div class="col-8 cell">{{ $data['id_oaid'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Id cliente</strong></div>
                        <div class="col-8 cell">{{ $data['id_cliente'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Resultado cotejo</strong></div>
                        <div class="col-8 cell">{{ $data['resultado_cotejo'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Primer nombre</strong></div>
                        <div class="col-8 cell">{{ $data['primer_nombre'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Segundo Nombre</strong></div>
                        <div class="col-8 cell">{{ $data['segundo_nombre'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Codigo Particula</strong></div>
                        <div class="col-8 cell">{{ $data['codigo_particula'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Descripcion particula</strong></div>
                        <div class="col-8 cell">{{ $data['descripcion_particula'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Primer Apellido</strong></div>
                        <div class="col-8 cell">{{ $data['primer_apellido'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Segundo Apellido</strong></div>
                        <div class="col-8 cell">{{ $data['segundo_apellido'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Lugar Expedicion</strong></div>
                        <div class="col-8 cell">{{ $data['lugar_expedicion'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Fecha Expedicion</strong></div>
                        <div class="col-8 cell">{{ $data['fecha_expedicion'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Codigo Vigencia</strong></div>
                        <div class="col-8 cell">{{ $data['codigo_vigencia'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 header-cell"><strong>Descripcion Vigencia</strong></div>
                        <div class="col-8 cell">{{ $data['descripcion_vigencia'] }}</div>
                    </div>
                    <!--<div class="row">
                        <div class="col-4 header-cell"><strong>message_error</strong></div>
                        <div class="col-8 cell">{{ $data['message_error'] }}</div>
                    </div>-->
                </div>
                <div class="col-3"></div>
            </div>
        </div>
        <a href="{{url('dash')}}" class="btn btn-primary mt-3">Volver</a></a>

    </div>

    <style>
        .loader-contenedor {
            margin-top: 25vh;
            width: 100vw;
            height: 200px;
            text-align:center;
            border:0px solid red;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #matcherResponse {
            display: none;
            margin-top:15vh;
        }

        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }


    </style>

    <script>
        // JavaScript para ocultar el div después de 3 segundos
        setTimeout(function() {
            document.getElementById('loader-contenedor').style.display = 'none';
            document.getElementById('matcherResponse').style.display = 'block';
        }, 3000); // 3000 milisegundos = 3 segundos
    </script>    

@endsection