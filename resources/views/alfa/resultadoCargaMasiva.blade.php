@extends('layouts.plantilla')

@section('contenido')
<div class="container">
    <div class="row mt-5">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Id Masiva</h5>
              <p class="card-text">{{ $logData['usuariocarga_id'] }}</p>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Parametro Actual</h5>
              <p class="card-text">Con soporte de texto adicional a continuación.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Registro Actual</h5>
              <p class="card-text">{{ $logData['totalregistros'] }}</p>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Total Registros</h5>
              <p class="card-text">{{ $logData['totalregistros'] }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <h2 class="text-success text-center">Iniciando Proceso 1 de {{ $logData['totalregistros'] }}</h2>
      </div>

</div>
@endsection
