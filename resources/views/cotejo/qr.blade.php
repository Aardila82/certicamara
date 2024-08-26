@extends('layouts.plantilla')

@section('contenido')
<div class="container mt-5 justify-content-center">
    <div class="row">
        <div class="col-12 text-center">
            <h1>QR Code Generado</h1>

        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-8 text-center">
            <img src="data:image/png;base64,{{ $qr }}" alt="QR Code">
        </div>
    </div>
</div>


<input type="hidden" id="latitud" name="latitud">
<input type="hidden" id="longitud" name="longitud">

<script>
    
</script>

@endsection
