@extends('layouts.plantilla')

@section('contenido')

<div class="container-fluid mt-5" >
    <h2 class="text-center mb-4">Log Facial</h2>

    <div class="row">
        <div class="col-md-12">
          <table id="dataTable" class="table table-striped table-bordered shadow-lg" style="width: 100%">
              <thead class="bg-primary text-white">
                    <tr>
                        <!--<th>ID</th>-->
                        <th>Cedula</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <!--<th>Usuario Carga</th>-->
                        <th>Resultado Cotejo</th>

                        <!--<th>Código Resultado</th>
                        <th>NUT (Response)</th>
                        <th>NUIP (Response)</th>
                        <th>ID Log</th>
                        <th>ID OAID</th>

                        <th>ID Cliente</th>
                        <th>Primer Nombre (Response)</th>
                        <th>Segundo Nombre (Response)</th>
                        <th>Código Partícula</th>

                        <th>Descripción Partícula</th>
                        <th>Primer Apellido (Response)</th>
                        <th>Segundo Apellido (Response)</th>
                        <th>Lugar Expedición</th>
                        <th>Fecha Expedición</th>

                        <th>Código Vigencia</th>
                        <th>Descripción Vigencia</th>
                        <th>Message Error</th>-->

                    </tr>
                </thead>
              </thead>
              <tbody>
                  @foreach ($logs as $log)
                  <tr>
                        <!--<td>{{$log->id}}</td>-->
                        <td>{{$log->nuip}}</td>
                        <td>{{$log->resultado}}</td>
                        <td>{{$log->fechainicio}}</td>
                        <td>{{$log->fechafin}}</td>
                        <!--<td>{{ $log->apellido1 }} {{ $log->apellido2 }} {{ $log->nombre1 }} {{ $log->nombre2 }}</td>
                        

                        <td>{{$log->codigo_resultado}}</td>
                        <td>{{$log->response_nut}}</td>
                        <td>{{$log->response_nuip}}</td>
                        <td>{{$log->id_log}}</td>
                        <td>{{$log->id_oaid}}</td>

                        <td>{{$log->id_cliente}}</td>
                        <td>{{$log->primer_nombre}}</td>
                        <td>{{$log->segundo_nombre}}</td>
                        <td>{{$log->codigo_particula}}</td>

                        <td>{{$log->descripcion_particula}}</td>
                        <td>{{$log->primer_apellido}}</td>
                        <td>{{$log->segundo_apellido}}</td>
                        <td>{{$log->lugar_expedicion}}</td>
                        <td>{{$log->fecha_expedicion}}</td>

                        <td>{{$log->codigo_vigencia}}</td>
                        <td>{{$log->descripcion_vigencia}}</td>
                        <td>{{$log->message_error}}</td>-->
                    </tr>
                  @endforeach
                  <!-- Agrega más filas según sea necesario -->
              </tbody>
          </table>
        </div>
        <div class="col-md-12 text-center">
          <a type="button" class="btn btn-secondary" href="../masiva">Atras</a>
        </div>
        <!-- Botón para descargar el CSV -->
    <form action="{{ url('log/facial-export') }}/{{$id}}" method="GET">
        <button class="btn btn-primary" type="submit">Descargar TXT</button>
    </form>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            "language": {
                "search": "Buscar",
                "emptyTable": "No hay datos disponibles",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                "lengthMenu": "Mostrar _MENU_ entradas por página",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "lengthMenu":[[25,50, 100, 200],[25,50,100,200, "All"]]
        });
    });
</script>

@endsection

