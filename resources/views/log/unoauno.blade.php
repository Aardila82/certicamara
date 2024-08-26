@extends('layouts.plantilla')

@section('titulo', 'Log Uno a Uno')

@section('contenido')

<div class="container-fluid mt-5">
    <h2 class="text-center mb-4">Log uno a uno</h2>

    <div class="row">
        <div class="col-md-12">
          <table id="dataTable" class="table table-striped table-bordered shadow-lg" style="width: 100%">
              <thead class="bg-primary text-white">
                  <tr>
                      <th>ID</th>
                      <th>NUT</th>
                      <th>NUIP</th>
                      <th>Estado del ATDP</th>

                      <th>Fecha Fin</th>
                      <th>Hash</th>
                      <th>Resultado Cotejo</th>
                      <th>Descargar ATDP</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($posts as $post)
                  <tr>
                      <td>{{ $post->id }}</td>
                      <td>{{ $post->nut }}</td>
                      <td>{{ $post->nuip }}</td>
                      <td>
                            @if($post->aprobacion_atdp)
                                aprobado
                            @else
                                <span class="text-danger"><strong>rechazado</strong></span>
                            @endif
                       </td>

                      <td>{{ $post->fechafin }}</td>
                      <td>{{ $post->hash256 }}</td>
                      <td>{{ strtoupper($post->resultado) }}</td>
                      <td> 
                        @if(!empty($post->atdpruta))
                            <a href="{{ route('download.pdf', $post->atdpruta) }}" class= "mb-3"><i class="fa fa-solid fa-download" aria-hidden="true"></i></a>
                        @endif                        
                      </td>
                  </tr>
                  @endforeach
              </tbody>
          </table>
        </div>
        <div class="col-md-12 text-center">
          <a type="button" class="btn btn-secondary" href="../log/menu">Atrás</a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12 text-end">
            <a href="{{ route('log/unoauno-export') }}" class="btn btn-primary">Descargar TXT</a>
        </div>
    </div>

</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
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
            "lengthMenu": [[25, 50, 100], [25, 50, 100, "All"]]
        });
    });
</script>
@endsection
