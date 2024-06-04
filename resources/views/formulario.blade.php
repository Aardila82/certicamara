<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario Bootstrap 5</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <h2>Formulario de Usuario</h2>
      </div>
    </div>
    <div id="mensaje-guardado" class="alert alert-success" style="display: none;" role="alert">
      ¡Tu formulario ha sido guardado exitosamente!
    </div>
    <form action="{{url('usuario/creacion')}}" method="POST" id="formu">

      <div class="row">

        @csrf <!-- Agrega el token CSRF para protección contra falsificación de solicitudes entre sitios -->

        <div class="mt-3  col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="nombre" class="form-label">N° de Documento</label>
          <input type="number" value="{{ old('numerodedocumento') }}"  id="numerodedocumento" name="numerodedocumento" class="form-control @error('numerodedocumento') is-invalid @enderror">
          @error('numerodedocumento')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="usuario" class="form-label">Usuario</label>
          <input type="text" value="{{ old('usuario') }}"  id="usuario" name="usuario" class="form-control @error('usuario') is-invalid @enderror">
          @error('usuario')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="nombre1" class="form-label">Primer Nombre</label>
          <input type="text" value="{{ old('nombre1') }}"  id="nombre1" name="nombre1" class="form-control @error('nombre1') is-invalid @enderror">
          @error('nombre1')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="nombre2" class="form-label">Segundo Nombre</label>
          <input type="text" value="{{ old('nombre2') }}"  id="nombre2" name="nombre2" class="form-control @error('nombre2') is-invalid @enderror">
          @error('nombre2')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="apellido1" class="form-label">Primer Apellido</label>
          <input type="text" value="{{ old('apellido1') }}"  id="apellido1" name="apellido1" class="form-control  @error('apellido1') is-invalid @enderror">
          @error('apellido1')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="apellido2" class="form-label">Segundo Apellido</label>
          <input type="text" value="{{ old('apellido2') }}"  id="apellido2" name="apellido2" class="form-control @error('apellido2') is-invalid @enderror">
          @error('apellido2')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="telefono" class="form-label">Teléfono</label>
          <input type="text" value="{{ old('telefono') }}"  id="telefono" name="telefono" class="form-control @error('telefono') is-invalid @enderror">
          @error('telefono')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="email" class="form-label">Email</label>
          <input type="text" value="{{ old('email') }}"  id="email" name="email" class="form-control @error('email') is-invalid @enderror">
          @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="departamento" class="form-label">Departamento</label>
          <select name="departamento" id="departamento" class="form-control  @error('departamento') is-invalid @enderror">
            <option value="">Selecciona un departamento</option>
            @foreach ($departamentos as $departamento)
              <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
            @endforeach
          </select>
          @error('departamento')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="municipio" class="form-label">Municipio</label>
          <select name="municipio" id="municipio" class="form-control  @error('municipio') is-invalid @enderror">
            <option value="">Selecciona un municipio</option>
          </select>
          @error('municipio')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="contrasena" class="form-label">Contraseña</label>
          <input type="password" value="{{ old('contrasena') }}" id="contrasena" name="contrasena" class="form-control  @error('contrasena') is-invalid @enderror">
          @error('contrasena')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="confirmacion_contrasena" class="form-label">Confirmación Contraseña</label>
          <input type="password" value="{{ old('confirmacion_contrasena') }}" id="confirmacion_contrasena" name="confirmacion_contrasena" class="form-control  @error('confirmacion_contrasena') is-invalid @enderror">
          @error('confirmacion_contrasena')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="rol" class="form-label">Roles</label>
          <select name="rol" id="rol" class="form-control  @error('rol') is-invalid @enderror">
            <option value="">Selecciona un rol</option>
            @foreach ($roles as $rol)
              <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
            @endforeach
          </select>
          @error('rol')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mt-3 col col-12 col-sm-12 col-md-6 col-lg-6">
          <label for="estado" class="form-label">Estado:</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="estado" name="estado" value="1" checked>
            <label class="form-check-label" for="estado">Activo</label>
          </div>
        </div>

        <div class="mt-3 col-12">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <a href="menu" class="btn btn-warning text-white">Atrás</a>
        </div>
      </div>
    </form>
  </div>

  <script>
    $(document).ready(function() {
      $('#departamento').on('change', function() {
        var departamentoId = $(this).val();
        if (departamentoId) {
          $.ajax({
            url: '/departamentos/' + departamentoId + '/municipios',
            type: "GET",
            dataType: "json",
            success: function(data) {
              $('#municipio').empty();
              $('#municipio').append('<option value="">Selecciona un municipio</option>');
              $.each(data, function(key, value) {
                $('#municipio').append('<option value="' + value.id + '">' + value.nombre + '</option>');
              });
            }
          });
        } else {
          $('#municipio').empty();
          $('#municipio').append('<option value="">Selecciona un municipio</option>');
        }
      });
    });
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
