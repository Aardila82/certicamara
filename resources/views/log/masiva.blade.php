@extends('layouts.plantilla')



@section('titulo', 'Log Masivas')

@section('contenido')
<div class="container-fluid mt-5" >
    <h2 class="text-center mb-4">Log Masivas</h2>

    <div class="row">
        <div class="col-md-12">
          <table id="dataTable" class="table table-striped table-bordered shadow-lg" style="width: 100%">
              <thead class="bg-primary text-white">
                  <tr>
                      <th>ID</th>
                      <th>Fecha Inicio</th>
                      <th>Fecha Fin</th>
                      <th>Usuario</th>
                      <th>Total Registros</th>
                      <th>Total Errores</th>
                      <th>Tiempo Total</th>
                      <th>Ver Detalle</th>

                    </tr>
              </thead>
              <tbody>
                  @foreach ($logs as $log)
                  <tr>
                      <td>{{$log->id}}</td>
                      <td>{{$log->fechainicio}}</td>
                      <td>{{$log->fechafin}}</td>
                      <td>{{$log->usuario_carga}}</td>
                      <td>{{$log->totalregistros}}</td>
                      <td>{{$log->errortotalregistros}}</td>
                      <td>{{$log->diferencia_segundos}}</td>
                      <td><a href="../log/facial/{{$log->id}}">Ver</a></td>
                  </tr>
                  @endforeach

              </tbody>
          </table>
        </div>
        <div class="col-md-12 text-center">
          <a type="button" class="btn btn-secondary" href="../usuario/reporte">Atras</a>
          <a href="{{ route('download.zip') }}" class="btn btn-primary">Descargar ZIP</a>
        </div>
    </div>
</div>
<!-- Pop-up de Editar -->



  <!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
            "lengthMenu":[[25,50,100],[25,50,100, "All"]]
        });
    });
</script>




@endsection

