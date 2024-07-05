@extends('layouts.plantilla')

@section('titulo', 'Lista de Errores')

@section('contenido')

<div class="container-fluid justify-content-center align-items-center">
    <div class="alert alert-success fs-3 text-center" role="alert">
        Guardado Exitoso!!!
    </div>

    <h2 class="text-center mb-4">Lista de Errores - Tiempo ejecución {{$tiempoTotal}} segundos</h2>

    <table id="dataTable" class="table table-striped table-bordered shadow-lg">
        <thead class="bg-primary text-white">
            <tr>
                <th>PIN</th>
                <th>Primer Nombre</th>
                <th>Segundo Nombre</th>
                <th>partícula</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th>explugar</th>
                <th>expfecha</th>
                <th>vigencia</th>
                <th>Error</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $error)
            <tr>
                <td>{{$error['pin']}}</td>
                <td>{{$error['nombre1']}}</td>
                <td>{{$error['nombre2']}}</td>
                <td>{{$error['partícula']}}</td>
                <td>{{$error['apellido1']}}</td>
                <td>{{$error['apellido2']}}</td>
                <td>{{$error['explugar']}}</td>
                <td>{{$error['expfecha']}}</td>
                <td>{{$error['vigencia']}}</td>
                <td>{{$error['error']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="dash" class="btn btn-primary mt-3">Volver</a>
</div>

<!-- jQuery (necesario para DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
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
            "lengthMenu": [
                [25, 50, 100],
                [25, 50, 100, "All"]
            ]
        });
    });
</script>

@endsection
