@extends('layouts.plantilla')

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-2 text-right">
            <label for="cedula">Ingresa tu Cédula: </label>
        </div>
        <div class="col-3">
            <form action="{{ route('capturar.cedula') }}" method="POST">
                @csrf
                <input type="text" name="cedula" id="cedula" class="form-control">
                <button type="submit" class="btn btn-primary mt-2">Enviar</button>
            </form>
        </div>
    </div>
</div>
@endsection
