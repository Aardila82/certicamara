@extends('layouts.plantilla')

@section('contenido')
<div class="container mt-5 text-center">
    <h1>El número de cédula ingresado es: {{ $cedula }}</h1>
    <h2>{{ $mensaje }}</h2>
    <form action="{{ route('generar.pdf') }}" method="POST">
        @csrf
        <input type="hidden" name="cedula" value="{{ $cedula }}">
        <input type="hidden" name="mensaje" value="{{ $mensaje }}">
        <button type="submit" class="btn btn-primary mt-3">Generar PDF</button>
    </form>
</div>
@endsection
