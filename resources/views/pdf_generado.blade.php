@extends('layouts.plantilla')

@section('contenido')
<div class="container mt-5 text-center">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h1>Pdf generado por favor dar en aceptar para continuar biometría facial</h1>
    <form action="{{ route('generar.pdf') }}" method="POST">
        @csrf
        <input type="hidden" name="cedula" value="{{ session('cedula') }}">
        <input type="hidden" name="mensaje" value="{{ session('mensaje') }}">
        <button type="submit" class="btn btn-success mt-3">Aceptar términos y condiciones </button>
        <a type="button" class="btn btn-secondary mt-3" href="dash">Atras</a>
    </form>
</div>
@endsection
