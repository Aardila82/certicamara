<!DOCTYPE html>
<html>
<head>
    <title>Cédula PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>El número de cédula ingresado es: {{ $cedula }}</h1>
        <h2>{{ $mensaje }}</h2>
    </div>
</body>
</html>
