<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario Bootstrap 5</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
          <div class="col-md-12 text-center">
            <h2>Formulario de Usuario</h2>
          </div>
        </div>
        <!-- Resto del formulario aquí -->
      </div>
<div class="container mt-3">
  <div class="row">
    <div class="col-md-6">
      <form action="{{url('/')}}" method="POST">
        @csrf <!-- Agrega el token CSRF para protección contra falsificación de solicitudes entre sitios -->
        <div class="mb-3">
            <label for="nombre1" class="form-label">Primer Nombre</label>
            <input type="text" id="nombre1" name="nombre1" class="form-control @error('nombre1') is-invalid @enderror">
            @error('nombre1')
                <div class="invalid-feedback">El primer nombre es obligatorio</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="apellido2" class="form-label">Primer Apellido</label>
            <input type="text" id="apellido1" name="apellido1" class="form-control  @error('apellido1') is-invalid @enderror">
            @error('apellido1')
            <div class="invalid-feedback">El primer Apellido es obligatorio</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="nombre" class="form-label">N° de Documento</label>
            <input type="number" id="numerodedocumento" name="numerodedocumento" class="form-control @error('numerodedocumento') is-invalid @enderror" >
            @error('numerodedocumento')
            <div class="invalid-feedback">El documento es obligatorio</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" id="telefono" name="telefono" class="form-control @error('telefono') is-invalid @enderror" >
            @error('telefono')
            <div class="invalid-feedback">El teléfono obligatorio</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="usuario" class="form-label">Municipio</label>
            <input type="number" id="municipio" name="municipio" class="form-control @error('municipio') is-invalid @enderror" >
            @error('telefono')
            <div class="invalid-feedback">El municipio obligatorio</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="confirmarContrasena" class="form-label">Contraseña</label>
            <input type="text" id="contrasena" name="contrasena" class="form-control  @error('municipio') is-invalid @enderror" >
            @error('telefono')
            <div class="invalid-feedback">El Contraseña obligatorio</div>
            @enderror
          </div>

      </div>
      <div class="col-md-6">
        <div class="mb-3">
            <label for="nombre2" class="form-label">Segundo Nombre</label>
            <input type="text" id="nombre2" name="nombre2" class="form-control @error('nombre2') is-invalid @enderror">
            @error('nombre2')
                <div class="invalid-feedback">El segundo nombre es obligatorio</div>
            @enderror
        </div>
          <div class="mb-3">
            <label for="apellido2" class="form-label">Segundo Apellido</label>
            <input type="text" id="apellido2" name="apellido2" class="form-control @error('apellido2') is-invalid @enderror">
            @error('apellido2')
                <div class="invalid-feedback">El segundo Apellido es obligatorio</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="fechaNacimiento" class="form-label">email</label>
            <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror" >
            @error('email')
                <div class="invalid-feedback">El email es obligatorio</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="telefono" class="form-label">Departamento</label>
            <input type="number" id="departamento" name="departamento" class="form-control @error('departamento') is-invalid @enderror">
            @error('departamento')
                <div class="invalid-feedback">El departamento es obligatorio</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="contrasena" class="form-label">Usuario</label>
            <input type="text" id="usuario" name="usuario" class="form-control @error('usuario') is-invalid @enderror" >
            @error('usuario')
                <div class="invalid-feedback">El usuario es obligatorio</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="estado" class="form-label">Estado:</label>
          <div class="form-check">
              <input class="form-check-input" type="checkbox" id="estado" name="estado" value="1" checked>
              <label class="form-check-label" for="estado" >Activo</label>
          </div>
          </div>

        </div>
        <div class="col-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/dash" class="btn btn-warning text-white">Atrás</a>
          </div>
      </form>

  </div>
</div>

<!-- Pop-up para confirmación -->
<div class="modal fade" id="confirmacionModal" tabindex="-1" aria-labelledby="confirmacionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmacionModalLabel">Guardado exitoso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¡Tu formulario ha sido guardado exitosamente!
      </div>
      <div class="modal-footer">
        <button type="button" id="cerrarModal" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>

<script>
  // Evento para el envío del formulario
  $('#formulario').submit(function(event) {
    event.preventDefault(); // Evita el comportamiento predeterminado del formulario
    $('#confirmacionModal').modal('show'); // Muestra el modal de confirmación
    // Aquí podrías agregar el código para enviar los datos del formulario a tu servidor
  });

  // Evento para cerrar el modal cuando se hace clic en el botón "Cerrar"
  $('#cerrarModal').click(function() {
    $('#confirmacionModal').modal('hide'); // Oculta el modal de confirmación
  });
</script>

</body>
</html>
