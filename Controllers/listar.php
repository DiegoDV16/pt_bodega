<?php
include("../conexion.php");

$estado = $_GET['estado'] ?? '';
$encargado = $_GET['encargado'] ?? '';
$busqueda = $_GET['busqueda'] ?? '';
$desde = $_GET['desde'] ?? '';
$hasta = $_GET['hasta'] ?? '';

// SENTENCIA SQL CON CONCAT PARA OBTENER NOMBRE COMPLETO DEL ENCARGADO 
$query = "SELECT b.id, b.codigo, b.nombre,
b.direccion, b.dotacion, b.fecha_creacion,b.estado_id,
be.encargado_id, e.nombre AS estado,
CONCAT(en.nombre,' ',en.primer_apellido,' ',en.segundo_apellido) AS encargado

FROM bodega b
LEFT JOIN estado e ON b.estado_id = e.id
LEFT JOIN bodega_encargado be ON b.id = be.bodega_id
LEFT JOIN encargado en ON be.encargado_id = en.id
WHERE 1=1";

if ($estado != "") {
    $query .= " AND b.estado_id = $estado";
}

if ($encargado != "") {
    $query .= " AND be.encargado_id = $encargado";
}

if ($desde != "" && $hasta != "") {
    $query .= " AND b.fecha_creacion::date BETWEEN '$desde' AND '$hasta'";
}

$result = pg_query($conn, $query);

$data = [];

while ($row = pg_fetch_assoc($result)) {

    // FORMATEAR FECHA A DD/MM/YYYY
    $row['fecha_creacion'] = date('d/m/Y', strtotime($row['fecha_creacion']));

    $data[] = $row;
}

echo json_encode($data);
?>