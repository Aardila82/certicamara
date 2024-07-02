@extends('layouts.plantilla')

@section('contenido')

<div class="container text-center d-block mx-auto">
    <div class="row">
        <div class="col-12">
            <h1 class="mt-5">Menú Principal</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-green">
                <div class="inner">
                    <h3></h3>
                    <p><b>Gestion Usuarios</b></p>
                </div>
                <div class="icon">
                    <i class=" fa fa-solid fa-user" aria-hidden="true"></i>
                </div>
                <a href="usuario/menu" class="card-box-footer">
                    Ver Mas
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-blue">
                <div class="inner">
                    <h3></h3>
                    <p><b>Carga Masiva</b></p>
                </div>
                <div class="icon">
                    <i class="fa fa-solid fa-share" aria-hidden="true"></i>
                </div>
                <a href="loader" class="card-box-footer" id="masiva">
                    Ver Mas
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-orange">
                <div class="inner">
                    <h3></h3>
                    <p><b>Reporte Logs</b></p>
                </div>
                <div class="icon">
                    <i class="fa fa-solid fa-check" aria-hidden="true"></i>
                </div>
                <a href="usuario/reporte" class="card-box-footer">
                    Ver Mas
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-red">
                <div class="inner">
                    <h3></h3>
                    <p><b>Cotejo Uno a Uno</b></p>
                </div>
                <div class="icon">
                    <i class="fa fa-solid fa-eye" aria-hidden="true"></i>
                </div>
                <a href="log/unoauno" class="card-box-footer">
                    Ver Mas
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="card-box bg-green">
                <div class="inner">
                    <h3></h3>
                    <p><b>Carga Archivo Alfa</b></p>
                </div>
                <div class="icon">
                    <i class="fa fa-solid fa-download" aria-hidden="true"></i>
                </div>
                <a href="importaralfa" class="card-box-footer" id="miBoton">
                    Ver Mas
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="avisoCargando" id="avisoCargando">Cargando...</div>
<div class="avisoCargando" id="avisoCargandoMasiva">Cargando...</div>

<style>
    body {
        background: #eee;
    }

    .card-box {
        position: relative;
        color: #fff;
        padding: 20px 10px 40px;
        margin: 20px 0px;
    }

    .card-box:hover {
        text-decoration: none;
        color: #f1f1f1;
    }

    .card-box:hover .icon i {
        font-size: 100px;
        transition: 1s;
        -webkit-transition: 1s;
    }

    .card-box .inner {
        padding: 5px 10px 0 10px;
    }

    .card-box h3 {
        font-size: 27px;
        font-weight: bold;
        margin: 0 0 8px 0;
        white-space: nowrap;
        padding: 0;
        text-align: left;
    }

    .card-box p {
        font-size: 15px;
    }

    .card-box .icon {
        position: absolute;
        top: auto;
        bottom: 5px;
        right: 5px;
        z-index: 0;
        font-size: 72px;
        color: rgba(0, 0, 0, 0.15);
    }

    .card-box .card-box-footer {
        position: absolute;
        left: 0px;
        bottom: 0px;
        text-align: center;
        padding: 3px 0;
        color: rgba(255, 255, 255, 0.8);
        background: rgba(0, 0, 0, 0.1);
        width: 100%;
        text-decoration: none;
    }

    .card-box:hover .card-box-footer {
        background: rgba(0, 0, 0, 0.3);
    }

    .bg-blue {
        background-color: #00c0ef !important;
    }

    .bg-green {
        background-color: #00a65a !important;
    }

    .bg-orange {
        background-color: #f39c12 !important;
    }

    .bg-red {
        background-color: #d9534f !important;
    }

    .avisoCargando {
        background-color: #f9f9f9;
        color: #333;
        padding: 10px;
        border: 1px solid #ccc;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none; /* Ocultarlo inicialmente */
        z-index: 10000;
    }

    #miBoton:hover {
        background-color: #3e8e41;
    }

    #miBoton:active {
        background-color: #2c6934;
    }
</style>

<script>
    // JavaScript para mostrar el aviso de "Cargando..." en carga Alfa
    const boton = document.getElementById('miBoton');
    const aviso = document.getElementById('avisoCargando');
    let isNavigating = false;

    boton.addEventListener('click', (event) => {
        event.preventDefault();
        isNavigating = true;
        aviso.style.display = 'block';
        window.location.href = boton.href;
    });

    window.addEventListener('beforeunload', function (event) {
        if (isNavigating) {
            aviso.style.display = 'block';
        } else {
            aviso.style.display = 'none';
        }
    });

    window.addEventListener('popstate', function (event) {
        aviso.style.display = 'none';
        isNavigating = false;
    });

    window.addEventListener('pageshow', function (event) {
        aviso.style.display = 'none';
        isNavigating = false;
    });


    // JavaScript para mostrar el aviso de "Cargando..." en carga Masiva
    const botonMasiva = document.getElementById('masiva');
    const avisoMasiva = document.getElementById('avisoCargandoMasiva');
    let isNavigatingMasiva = false;

    botonMasiva.addEventListener('click', (event) => {
        event.preventDefault();
        isNavigatingMasiva = true;
        avisoMasiva.style.display = 'block';
        window.location.href = botonMasiva.href;
    });

    window.addEventListener('beforeunload', function (event) {
        if (isNavigating) {
            aviso.style.display = 'block';
        } else {
            aviso.style.display = 'none';
        }
    });

    window.addEventListener('popstate', function (event) {
        aviso.style.display = 'none';
        isNavigating = false;
    });

    window.addEventListener('pageshow', function (event) {
        aviso.style.display = 'none';
        isNavigating = false;
    });
</script>

@endsection
