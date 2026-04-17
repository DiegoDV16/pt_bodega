<?php
include("../conexion.php");

$id = $_POST['id'];
$codigo = $_POST['codigo'];
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$dotacion = $_POST['dotacion'];
$estado = $_POST['estado'];
$encargado = $_POST['encargado'];

try {

    //ACTUALIZAR BODEGA (INCLUYE CÓDIGO)
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