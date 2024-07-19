@extends('layouts.plantilla')

@section('contenido')
<div class="container mt-5 justify-content-center">
    <div class="row">
        <div class="col-12 text-center">
            <h1>Ingresa tu Cédula: </h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-8 text-center">
            <form action="{{ route('capturar.cedula') }}" method="POST">
                @csrf
                <input type="text" name="cedula" id="cedula" class="form-control" placeholder="Ingresa tu Cédula" required>

                @if ($errors->has('cedula'))
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('cedula') }}
                    </div>
                @endif

                <button type="submit" class="btn btn-success mt-2">Validar Cédula</button>
            </form>
        </div>
    </div>
</div>
@endsection
