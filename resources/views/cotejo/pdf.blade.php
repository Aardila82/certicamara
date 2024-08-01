@extends('layouts.plantilla')

@section('contenido')
<div class="container d-flex flex-column justify-content-center align-items-center" >
    <div class="col-md-6 text-center mt-5">
        <h1 class="mb-4">Generación de resultados ATDP </h1>

    </div>

    <div class="col">
        <a href="{{ route('download.pdf', $documento) }}" class="btn btn-primary mb-3">Descargar PDF</a>
        <a href="{{ route('connectliveness', $cedula) }}" class="btn btn-success mb-3">Continuar Cotejo</a>
    </div>

</div>
@endsection
