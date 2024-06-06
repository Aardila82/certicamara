@extends('layouts.plantilla')

@section('contenido')

<div class="container-fluid mt-5" >
    <h2 class="text-center mb-4">Log Facial</h2>

    <div class="row">
        <div class="col-md-12">
          <table id="dataTable" class="table table-striped table-bordered shadow-lg" style="width: 100%">
              <thead class="bg-primary text-white">
                  <tr>
                      <th>ID</th>
                      <th>NUT</th>
                      <th>NUIP</th>
                      <th>Resultado</th>
                      <th>Fecha Fin</th>
                      <th>ID Usuario</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($logs as $log)
                  <tr>
                      <td>{{$log->id}}</td>
                      <td>{{$log->NUT}}</td>
                      <td>{{$log->NUIP}}</td>
                      <td>{{$log->resultado}}</td>
                      <td>{{$log->fechaFin}}</td>
                      <td>{{$log->idUsuuario}}</td>
                  </tr>
                  @endforeach
                  <!-- Agrega más filas según sea necesario -->
              </tbody>
          </table>
        </div>
        <div class="col-md-12 text-center">
          <a type="button" class="btn btn-secondary" href="../usuario/reporte">Atras</a>
        </div>
    </div>
</div>
<!-- Pop-up de Editar -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editarModalLabel">Editar Registro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Aquí puedes colocar el formulario de edición -->
          <form>
            <!-- Campos de edición aquí -->
            <div class="container mt-3">
                <div class="row">
                  <div class="col-md-6">
                    <form id="formulario">
                      <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" required>
                      </div>
                      <div class="mb-3">
                          <label for="apellido2" class="form-label">Segundo Apellido</label>
                          <input type="text" class="form-control" id="apellido2">
                        </div>
                        <div class="mb-3">
                          <label for="nombre" class="form-label">N° de Documento</label>
                          <input type="text" class="form-control" id="nombre" required>
                        </div>
                        <div class="mb-3">
                          <label for="correo" class="form-label">Correo Electrónico</label>
                          <input type="email" class="form-control" id="correo" required>
                        </div>
                        <div class="mb-3">
                          <label for="usuario" class="form-label">Usuario</label>
                          <input type="text" class="form-control" id="usuario" required>
                        </div>
                        <div class="mb-3">
                          <label for="confirmarContrasena" class="form-label">Confirmar Contraseña</label>
                          <input type="password" class="form-control" id="confirmarContrasena" required>
                        </div>
                        <div class="mb-3">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="activo">
                            <label class="form-check-label" for="activo">Activo</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inactivo">
                            <label class="form-check-label" for="inactivo">Inactivo</label>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                          <label for="apellido1" class="form-label">Primer Apellido</label>
                          <input type="text" class="form-control" id="apellido1" required>
                        </div>
                        <div class="mb-3">
                          <label for="tipoDocumento" class="form-label">Tipo de Documento</label>
                          <select class="form-select" id="tipoDocumento">
                            <option selected disabled>Selecciona un tipo de documento</option>
                            <option value="cedula">Cédula</option>
                            <option value="pasaporte">Pasaporte</option>
                            <option value="cedulaExtranjeria">Cédula de Extranjería</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                          <input type="date" class="form-control" id="fechaNacimiento" required>
                        </div>
                        <div class="mb-3">
                          <label for="telefono" class="form-label">Teléfono</label>
                          <input type="tel" class="form-control" id="telefono" required>
                        </div>
                        <div class="mb-3">
                          <label for="contrasena" class="form-label">Contraseña</label>
                          <input type="password" class="form-control" id="contrasena" required>
                        </div>

                      </div>
                      <div class="col-3">
                          <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>

                </div>
              </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Pop-up de Eliminar -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="eliminarModalLabel">Confirmar Eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ¿Estás seguro de que deseas eliminar este registro?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger">Eliminar</button>
        </div>
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
            "lengthMenu":[[5,10,25,50],[5,10,25,50, "All"]]
        });
    });
</script>

@endsection

