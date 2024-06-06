@extends('layouts.plantilla')

@section('contenido')


<div class="container-fluid mt-5" >
    <h2 class="text-center mb-4">Lista de Usuarios</h2>

    <div class="row">
        <div class="col-md-12">
          <table id="dataTable" class="table table-striped table-bordered shadow-lg" style="width: 100%">
              <thead class="bg-primary text-white">
                  <tr>
                      <th>ID</th>
                      <th>Primer Nombre</th>
                      <th>Segundo Nombre</th>
                      <th>Primer Apellido</th>
                      <th>Segundo Apellido</th>
                      <th>N° de Documento</th>
                      <th>Email</th>
                      <th>Telefono</th>
                      <th>Departamento</th>
                      <th>Municipio</th>
                      <th>Estado</th>
                      <th>Editar/Desactivar</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($usuarios as $usuario)
                  <tr>
                      <td>{{$usuario->id}}</td>
                      <td>{{$usuario->nombre1}}</td>
                      <td>{{$usuario->nombre2}}</td>
                      <td>{{$usuario->apellido1}}</td>
                      <td>{{$usuario->apellido2}}</td>
                      <td>{{$usuario->numerodedocumento}}</td>
                      <td>{{$usuario->email}}</td>
                      <td>{{$usuario->telefono}}</td>
                      <td>{{$usuario->departamento_nombre}}</td>
                      <td>{{$usuario->municipio_nombre}}</td>
                      <td>{{$usuario->estado ? 'Activo' : 'Inactivo' }}</td>
                      <td>
                        <a href="{{ route('usuario.edit', $usuario->id) }}" class="btn btn-primary btn-sm me-2" style="font-size: 0.7rem; color:white;">
                            Editar
                        </a>
                        <button type="button" class="btn btn-sm estado-usuario" data-id="{{$usuario->id}}" style="font-size: 0.7rem; background-color: {{$usuario->estado ? 'red' : 'green'}}; color:white;">
                            {{$usuario->estado ? 'Desactivar' : 'Activar'}}
                        </button>
                      </td>
                  </tr>
                  @endforeach
                  <!-- Agrega más filas según sea necesario -->
              </tbody>
          </table>
        </div>
        <div class="col-md-12 text-center">
          <a type="button" class="btn btn-secondary" href="menu">Atras</a>
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
            "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50, "All"]]
        });

        // Manejar el cambio de estado del usuario
        $('.estado-usuario').on('click', function () {
            var userId = $(this).data('id');
            var button = $(this);
            var currentState = button.text().trim() === 'Desactivar';
            var newState = currentState ? 0 : 1;

            $.ajax({
                url: '{{ route("usuario.cambiarEstado", "") }}/' + userId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    estado: newState
                },
                success: function (response) {
                    if (response.success) {
                        var newStatusText = newState ? 'Desactivar' : 'Activar';
                        var newButtonColor = newState ? 'red' : 'green';
                        button.text(newStatusText);
                        button.css('background-color', newButtonColor);
                        button.closest('tr').find('td:eq(10)').text(newState ? 'Activo' : 'Inactivo');
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert('Ocurrió un error al cambiar el estado del usuario.');
                }
            });
        });
    });
</script>

@endsection
