@extends('layouts.plantilla')

@section('contenido')

<div class="container-fluid justify-content-center align-items-center">
    <div class="alert alert-success fs-3 text-center" role="alert">
        Guardado Exitoso!!!
    </div>


    <h2 class="text-center mb-4">Lista de Errores - Tiempo ejecución {{$tiempoTotal}} segundos</h2>

    <table id="dataTable" class="table table-striped table-bordered shadow-lg" >
        <thead class="bg-primary text-white">
            <tr>
                <th>PIN</th>
                <th>Primer Nombre</th>
                <th>Segundo Nombre</th>
                <th>partícula</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>

                <th>explugar</th>
                <th>expfecha</th>
                <th>vigencia</th>
                <th>Error</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $error)
            <tr>
                <td>{{$error['pin']}}</td>
                <td>{{$error['nombre1']}}</td>
                <td>{{$error['nombre2']}}</td>
                <td>{{$error['partícula']}}</td>
                <td>{{$error['apellido1']}}</td>
                <td>{{$error['apellido2']}}</td>

                <td>{{$error['explugar']}}</td>
                <td>{{$error['expfecha']}}</td>
                <td>{{$error['vigencia']}}</td>
                <td>{{$error['error']}}</td>
            </tr>
            @endforeach
            <!-- Agrega más filas según sea necesario -->
        </tbody>
    </table>


    <a href="formulario" class="btn btn-primary mt-3">Volver</a>
</div>

<!-- Bootstrap JS (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

@endsection
