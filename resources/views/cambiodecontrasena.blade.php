<!-- resources/views/cambiodecontrasena.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de Contraseña</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
        }

        .vh-100 {
            height: 100%;
        }

        .h-100 {
            height: 100%;
        }
    </style>
</head>

<body>

    <section class="vh-100" style="background-color: gainsboro;">
        <div class="container py-4 h-100 d-flex justify-content-center align-items-center">
            <div class="col col-xl-6 col-lg-8 col-md-10">
                <div class="card" style="border-radius: 1rem;">
                    <div class="card-body p-4 p-lg-5 text-black">

                        <div class="d-flex justify-content-center mb-3 pb-1">
                            <img src="{{ URL::asset('/image/logo.jpg') }}" alt="login form" class="img-fluid" />
                        </div>

                        <h5 class="fw-normal mb-3 pb-3 text-center" style="letter-spacing: 1px;">Cambio de Contraseña</h5>

                        <form action="{{ route('cambiar.contrasena') }}" method="POST">
                            @csrf
                            <div class="form-outline mb-4">
                                <label class="form-label" for="email">Correo Electrónico</label>
                                <input type="email" name="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" required />
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="cedula">Cédula</label>
                                <input type="text" name="cedula" id="cedula" class="form-control form-control-lg @error('cedula') is-invalid @enderror" value="{{ old('cedula') }}" required />
                                @error('cedula')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-center pt-1 mb-4">
                                <input type="submit" value="Solicitar Cambio de Contraseña" class="btn btn-danger btn-lg btn-block">
                            </div>
                            <div class="d-flex justify-content-center pt-1 mb-4">
                                <a href="login">Volver</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
