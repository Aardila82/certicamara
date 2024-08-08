@extends('layouts.plantilla')

@section('contenido')
<div class="container mt-5 text-center">
    <h1>Firma ATDP</h1>
    {!! nl2br($mensaje) !!}<br>
    <form action="{{ route('generar.pdf') }}" method="POST">
        @csrf
        <input type="hidden" name="cedula" value="{{ $cedula }}">
        <input type="hidden" name="mensaje" value="{{ $mensaje }}">
        <button type="submit" class="btn btn-success mt-3" style="display:none;" >Aceptar términos y condiciones </button>
        <a href="{{ route('connectliveness', $cedula) }}" class="btn btn-success mt-3">Aceptar términos y condiciones </a>
        <a href="{{ route('rechazarcotejo', $cedula) }}" class="btn btn-danger mt-3">Rechazar</a>

    </form>
</div>
@endsection
