<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

    <title>@yield('titulo')</title>
  </head>
  <body>

    @yield('navegacion')
    <style>
        .navbar-custom {
            background-color: #f85130; /* Un tono gris oscuro */
        }
    </style>
</head>
<body>
    <div class="container mt-2">

        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
            <div class="container-fluid">
                <a class="navbar-brand" href="logout">Salir</a>
                @auth
                    <h6 class="text-white">Usuario: {{ Auth::user()->name }}!</h6>
                @endauth
            </div>
        </nav>
    </div>

         @yield('contenido')

  </body>

</html>




