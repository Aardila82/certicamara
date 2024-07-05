<!-- resources/views/actualizarcontrasena.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Contraseña</title>
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

                        <h5 class="fw-normal mb-3 pb-3 text-center" style="letter-spacing: 1px;">Actualizar Contraseña</h5>

                        <form action="{{ route('actualizar.contrasena') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="{{ request('email') }}">
                            <input type="hidden" name="cedula" value="{{ request('cedula') }}">

                            <div class="form-outline mb-4">
                                <label class="form-label" for="new_password">Nueva Contraseña</label>
                                <input type="password" name="new_password" id="new_password" class="form-control form-control-lg @error('new_password') is-invalid @enderror" required />
                                @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="new_password_confirmation">Confirmar Nueva Contraseña</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control form-control-lg @error('new_password_confirmation') is-invalid @enderror" required />
                                @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-center pt-1 mb-4">
                                <input type="submit" value="Actualizar Contraseña" class="btn btn-danger btn-lg btn-block">
                            </div>

                            <div class="d-flex justify-content-center pt-1 mb-4">
                                <a href="login">Atras</a>
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
