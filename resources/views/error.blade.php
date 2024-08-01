@extends('layouts.plantilla')

@section('titulo', 'Liveness')

@section('contenido')

<div class="container-fluid text-center d-block mx-auto">
        <div class="loader-contenedor" id="loader-contenedor">
            <div class="loader"></div>
        </div>

        <div class="row" id="matcherResponse">
            <h5>Respuesta de Matcher</h5>
            @foreach($matcherResponse as $key => $value)

                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-2 text-start"><strong>{{ $key }}</strong></div>
                    <div class="col-4">{{ $value }}</div>
                    <div class="col-3"></div>
                </div>
            @endforeach
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