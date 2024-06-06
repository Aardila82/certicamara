<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados Masiva</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Imprimir los datos en la consola del navegador
            console.log(@json($resultados));
        });
    </script>
</head>
<body>
    <h1>Resultados Masiva</h1>
    <pre>{{ json_encode($resultados, JSON_PRETTY_PRINT) }}</pre>
</body>
</html>
