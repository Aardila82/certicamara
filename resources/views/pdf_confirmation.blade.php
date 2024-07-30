@extends('layouts.plantilla')

@section('contenido')
<div class="container mt-5 text-center">
    <h1>Su archivo PDF ha sido creado</h1>
    <p>El número de cédula ingresado es: {{ $cedula }}</p>
    <p>Es necesario oprimir aceptar para el procedimiento de reconocimiento facial.</p>
    <a type="button" class="btn btn-success mt-3" href="{{ route('reconocimiento.facial') }}">Aceptar</a>
    <a type="button" class="btn btn-secondary mt-3" href="dash">Atras</a>
</div>
@endsection
