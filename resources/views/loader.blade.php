@extends('layouts.plantilla')

@section('titulo', 'Carga Masiva')

@section('contenido')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>



<!--<div id="loader" class="loader-overlay">
    <div class="loader"></div>
    <div class="loading-text">Cargando Masivas</div>
</div>-->

<div id="content" class="content-container text-center" >
    <!-- Aquí va el contenido de tu aplicación -->
    <!--<h2>Carga Masiva</h2>
    <p>Contenido {{$registros}} de {{$total}} ...</p>-->

    <div class="container mt-5">
        <h2>Carga de masiva</h2>
        <div class="progress">
            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
        </div>
    </div>

    <a href="{{url('dash')}}" class="btn btn-primary mt-3">Volver</a></a>

</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

<script>
    /*document.addEventListener('DOMContentLoaded', function () {
        var loader = document.getElementById('loader');
        var content = document.getElementById('content');

        // Mostrar el contenido después de 5 segundos
        setTimeout(function() {
            if (loader) {
                loader.style.display = 'none';
            }
            if (content) {
                content.style.display = 'flex';
            }
        }, 100); // 5000 milisegundos = 5 segundos
    });*/
    $(document).ready(function() {

        var totalRecords = 0;
        var loadedRecords = 0;

        function updateProgressBar() {
            $.ajax({
                url: '../loaderAjax/{{$idmasiva}}', // Reemplaza con la URL de tu API
                method: 'GET',
                success: function(response) {
                    // Asumiendo que la respuesta de la API tiene el siguiente formato:
                    // {
                    //     "totalRecords": 100,
                    //     "loadedRecords": 50
                    // }
                    totalRecords = response.total;
                    loadedRecords = response.registros;

                    if (totalRecords > 0) {
                        var progressPercentage = (loadedRecords / totalRecords) * 100;

                        $('#progress-bar').css('width', progressPercentage + '%');
                        $('#progress-bar').attr('aria-valuenow', progressPercentage);
                        $('#progress-bar').text(Math.round(progressPercentage) + '%');
                    }
                },
                error: function(error) {
                    console.log('Error al obtener los datos de la API', error);
                }
            });
        }

        // Llamar a la función por primera vez
        updateProgressBar();

        // Llamar a la función cada 10 segundos
        setInterval(updateProgressBar, 2000);
    });


</script>

@endsection
