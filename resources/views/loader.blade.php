@extends('layouts.plantilla')

@section('titulo', 'Carga Masiva')

@section('contenido')

<style>
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        flex-direction: column;
    }

    .loader {
        position: relative;
        width: 100px;
        height: 100px;
        margin-bottom: 20px;
    }

    .loader:before, .loader:after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 10px solid transparent;
        border-top-color: red;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .loader:after {
        border-color: transparent;
        border-top-color: grey;
        border-width: 5px;
        animation: spin 1s linear infinite reverse;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .loading-text {
        font-size: 20px;
        color: #333;
    }

    .content-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* 100% de la altura de la ventana gráfica */
        flex-direction: column;
    }

    .progress-bar {
        width: 80%;
        background-color: #e0e0e0;
        border-radius: 25px;
        overflow: hidden;
        margin-top: 20px;
    }

    .progress {
        height: 20px;
        width: 0%;
        background-color: #4caf50;
        text-align: center;
        line-height: 20px;
        color: white;
        border-radius: 25px;
    }
</style>

<div id="loader" class="loader-overlay">
    <div class="loader"></div>
    <div class="loading-text">Cargando Masivas</div>
</div>

<div id="content" class="content-container" style="display: none;">
    <!-- Aquí va el contenido de tu aplicación -->
    <h2>Carga Masiva</h2>
    <p>Contenido {{$registros}} de {{$total}} ...</p>
    <a href="{{url('dash')}}" class="btn btn-primary mt-3">Volver</a></a>
    <div class="progress-bar">
        <div id="progress" class="progress">0%</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var loader = document.getElementById('loader');
        var content = document.getElementById('content');
        var progress = document.getElementById('progress');

        // Simulación de carga con progreso
        var totalDuration = 5000; // 5000 milisegundos = 5 segundos
        var intervalDuration = 100; // Intervalo de actualización en milisegundos
        var elapsedTime = 0;

        var interval = setInterval(function() {
            elapsedTime += intervalDuration;
            var progressPercentage = Math.min(100, (elapsedTime / totalDuration) * 100);
            progress.style.width = progressPercentage + '%';
            progress.textContent = Math.floor(progressPercentage) + '%';

            if (elapsedTime >= totalDuration) {
                clearInterval(interval);
                if (loader) {
                    loader.style.display = 'none';
                }
                if (content) {
                    content.style.display = 'flex';
                }
            }
        }, intervalDuration);
    });
</script>

@endsection
