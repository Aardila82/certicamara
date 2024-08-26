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

        .aprobado {
            background-color: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .rechazado {
            background-color: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ATDP Cédula: {{ $cedula }}</h1>
        {!! nl2br($mensaje) !!}
        <BR><BR><BR>
        @if($estadoAprobacion === 'APROBADO')
            <div class="aprobado">
                <h2>APROBADO {{ $fechaActual }}</h2>
            </div>
        @else
            <div class="rechazado">
                <h2>RECHAZADO {{ $fechaActual }}</h2>
            </div>
        @endif
    </div>
</body>
</html>
