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
</style>

<div id="loader" class="loader-overlay">
    <div class="loader"></div>
    <div class="loading-text">Cargando Masivas</div>
</div>

<div id="content" class="content-container" style="display: none;">
    <!-- Aquí va el contenido de tu aplicación -->
    <h2>Carga Masiva</h2>
    <p>Contenido 1 de 5000...</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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
        }, 5000); // 5000 milisegundos = 5 segundos
    });
</script>

@endsection
