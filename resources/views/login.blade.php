<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap 5 Example</title>
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

                        <form action="login" method="POST">
                            @csrf
                            <div class="d-flex justify-content-center mb-3 pb-1">
                                <img src="{{URL::asset('/image/logo.jpg')}}" alt="login form" class="img-fluid" />
                            </div>

                            <h5 class="fw-normal mb-3 pb-3 text-center" style="letter-spacing: 1px;">Inicia sesión en tu cuenta</h5>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="email">Correo Electrónico</label>
                                <input type="email" value="{{ old('email') }}" name="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" />
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" />
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-center pt-1 mb-4">
                                <input type="submit" value="Ingresar" class="btn btn-danger btn-lg btn-block">
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
