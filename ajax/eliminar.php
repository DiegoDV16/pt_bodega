<?php
include("../conexion.php");

// VALIDAR ID
if (!empty($_POST['id'])) {

    $id = $_POST['id'];

    try {
        pg_query($conn, "BEGIN");

        // ELIMINAR RELACIÓN BODEGA_ENCARGADO ANTES DE ELIMINAR LA BODEGA
        $query1 = "DELETE FROM bodega_encargado WHERE bodega_id = $1";
        $res1 = pg_query_params($conn, $query1, [$id]);

        if (!$res1) {
            throw new Exception("Error al eliminar relación bodega_encargado");
        }

        // SENTENCIA SQL PARA ELIMINAR LA BODEGA
        $query2 = "DELETE FROM bodega WHERE id = $1";
        $res2 = pg_query_params($conn, $query2, [$id]);

        if (!$res2) {
            throw new Exception("Error al eliminar bodega");
        }
        pg_query($conn, "COMMIT");

        echo json_encode([
            "status" => "ok",
            "mensaje" => "Bodega eliminada correctamente"
        ]);
        exit;

    } catch (Exception $e) {

        pg_query($conn, "ROLLBACK");

        echo json_encode([
            "status" => "error",
            "mensaje" => $e->getMessage()
        ]);
        exit;
    }

} else {

    echo json_encode([
        "status" => "error",
        "mensaje" => "ID no recibido"
    ]);
    exit;
}
?>