<?php
include("conexion.php");
//SENTENCIA SQL PARA OBTENER ESTADOS Y ENCARGADOS PARA LOS SELECTS
$queryEstados = "SELECT id, nombre FROM estado ORDER BY id";
$resultEstados = pg_query($conn, $queryEstados);

$queryEncargados = "SELECT id, 
                    CONCAT(nombre, ' ', primer_apellido, ' ', segundo_apellido) AS nombre_completo 
                    FROM encargado 
                    ORDER BY id";

$resultEncargados = pg_query($conn, $queryEncargados);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Bodegas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand">Sistema Bodegas</span>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- FORMULARIO DE REGISTRO DE BODEGA -->
        <div class="card p-3 mb-3">
            <h6 class="mb-3 fw-bold text-primary">Registrar Bodega</h6>
            <!-- Campos para Código, Nombre, Dirección, Dotación, Estado y Encargado -->
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Código</label>
                    <input id="codigo" class="form-control" placeholder="EJ: BOD01">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nombre</label>
                    <input id="nombre" class="form-control" placeholder="Nombre">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Dirección</label>
                    <input id="direccion" class="form-control" placeholder="Dirección">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Dotación</label>
                    <input id="dotacion" type="number" min="0" class="form-control" placeholder="Dotación">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select id="estado" class="form-select">
                        <option value="">Estado</option>
                        <?php while ($e = pg_fetch_assoc($resultEstados)) { ?>
                            <option value="<?= $e['id']; ?>"><?= $e['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Encargado</label>
                    <select id="encargado" class="form-select">
                        <option value="">Encargado</option>
                        <?php while ($en = pg_fetch_assoc($resultEncargados)) { ?>
                            <option value="<?= $en['id']; ?>"><?= $en['nombre_completo']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Fecha de creación se autocompleta con la fecha actual y es de solo lectura -->
                <div class="col-md-3">
                    <label class="form-label">Fecha creación</label>
                    <input id="fecha_creacion" class="form-control bg-light" readonly>
                </div>
                <!-- Botón para guardar la nueva bodega -->
                <div class="col-md-3 d-grid">
                    <label class="form-label invisible">.</label>
                    <button onclick="crear()" class="btn btn-primary">Guardar</button>
                </div>

            </div>
        </div>
        <!--FIN FORMULARIO DE REGISTRO DE BODEGA-->
        <!--FILTROS DE BÚSQUEDA Y TABLA DE RESULTADOS-->
        <div class="card p-3 mb-3">
            <h6 class="mb-3 fw-bold text-success">Filtros de búsqueda</h6>
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select id="filtroEstado" onchange="listar()" class="form-select">
                        <option value="">Todos Estados</option>
                        <?php
                        $resultEstados = pg_query($conn, $queryEstados);
                        while ($e = pg_fetch_assoc($resultEstados)) { ?>
                            <option value="<?= $e['id']; ?>"><?= $e['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Encargado</label>
                    <select id="filtroEncargado" onchange="listar()" class="form-select">
                        <option value="">Todos Encargados</option>
                        <?php
                        $resultEncargados = pg_query($conn, $queryEncargados);
                        while ($en = pg_fetch_assoc($resultEncargados)) { ?>
                            <option value="<?= $en['id']; ?>"><?= $en['nombre_completo']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha desde</label>
                    <input type="date" id="fecha_desde" onchange="listar()" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha hasta</label>
                    <input type="date" id="fecha_hasta" onchange="listar()" class="form-control">
                </div>
            </div>
        </div>

        <div class="card p-3">
            <table class="table table-hover text-center">
                <!-- Encabezado de la tabla con columnas ptado -->
                <thead class="table-primary">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Dotación</th>
                        <th>Encargado</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla"></tbody>
            </table>
        </div>
        <!--FIN FILTROS DE BÚSQUEDA Y TABLA DE RESULTADOS-->
    </div>
    
    <!--  MODAL DE EDICIÓN DE BODEGA -->
    <?php include("modalEditarBodega.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>

</body>
</html>