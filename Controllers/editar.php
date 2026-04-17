<?php
include("../conexion.php");

// validacion de campos vacios
if (empty($_POST['id']) || empty($_POST['codigo']) ||empty($_POST['nombre']) || 
    empty($_POST['direccion']) ||empty($_POST['dotacion']) ||
    empty($_POST['estado']) || empty($_POST['encargado'])) {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Todos los campos son obligatorios"
    ]);
    exit;
}

$id = $_POST['id'];
$codigo = $_POST['codigo'];
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$dotacion = $_POST['dotacion'];
$estado = $_POST['estado'];
$encargado = $_POST['encargado'];

try {

   //Validacion de dotacion no puede ser menor a 0
    if (!filter_var($dotacion, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]])) {
        echo json_encode([
            "status" => "error",
            "mensaje" => "La dotación debe ser mayor o igual a 0"
        ]);
        exit;
    }

    // validacion de bodega con mismo codigo pero diferente id
    $sqlVal = "SELECT 1 FROM bodega WHERE codigo = $1 AND id != $2";
    $resVal = pg_query_params($conn, $sqlVal, [$codigo, $id]);

    if (pg_num_rows($resVal) > 0) {
        echo json_encode([
            "status" => "error",
            "mensaje" => "Ya existe otra bodega con ese código"
        ]);
        exit;
    }

    //ACTUALIZAR BODEGA
    $sql = "UPDATE bodega 
            SET codigo = $1,
                nombre = $2,
                direccion = $3,
                dotacion = $4,
                estado_id = $5
            WHERE id = $6";

    $result = pg_query_params($conn, $sql, [
        $codigo,
        $nombre,
        $direccion,
        $dotacion,
        $estado,
        $id
    ]);

    if (!$result) {
        throw new Exception("Error al actualizar bodega");
    }

    //ACTUALIZAR TABLA PIVOTE BODEGA_ENCARGADO
    $sql2 = "UPDATE bodega_encargado 
             SET encargado_id = $1
             WHERE bodega_id = $2";

    $result2 = pg_query_params($conn, $sql2, [
        $encargado,
        $id
    ]);

    if (!$result2) {
        throw new Exception("Error al actualizar encargado");
    }

    echo json_encode([
        "status" => "ok",
        "mensaje" => "Bodega actualizada correctamente"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "mensaje" => $e->getMessage()
    ]);
}
?>