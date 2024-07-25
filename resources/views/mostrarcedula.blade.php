@extends('layouts.plantilla')

@section('contenido')
<div class="container mt-5 text-center">
    <h1>El número de cédula ingresado es: {{ $cedula }}</h1>
    {!! nl2br($mensaje) !!}<br>
    <form action="{{ route('generar.pdf') }}" method="POST">
        @csrf
        <input type="hidden" name="cedula" value="{{ $cedula }}">
        <input type="hidden" name="mensaje" value="{{ $mensaje }}">
        <button type="submit" class="btn btn-success mt-3">Aceptar términos y condiciones </button>
        <a type="button" class="btn btn-secondary mt-3" href="dash">Atras</a>
    </form>
</div>
@endsection
