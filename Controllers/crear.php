<?php
include("../conexion.php");

// VALIDAR CAMPOS OBLIGATORIOS QUE NO VENGAN VACÍOS
if (!empty($_POST['codigo']) && !empty($_POST['nombre']) && !empty($_POST['direccion']) &&
    !empty($_POST['dotacion']) && !empty($_POST['estado']) &&!empty($_POST['encargado'])) {

    $codigo    = $_POST['codigo'];
    $nombre    = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $dotacion  = $_POST['dotacion'];
    $estado    = $_POST['estado'];
    $encargado = $_POST['encargado'];

    try {
         // VALIDAR QUE LA DOTACIÓN SEA UN NÚMERO ENTERO POSITIVO
        if (!filter_var($dotacion, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]])) {
            echo json_encode([
                "status" => "error",
                "mensaje" => "La dotación debe ser un número entero positivo"
            ]);
            exit;
        }
        //  VALIDAR QUE NO EXISTA OTRA BODEGA CON EL MISMO CÓDIGO
        $sql = "SELECT 1 FROM bodega WHERE codigo = $1";
        $res = pg_query_params($conn, $sql, [$codigo]);

        if (pg_num_rows($res) > 0) {

            echo json_encode([
                "status" => "error",
                "mensaje" => "No pueden haber dos bodegas con el mismo código"
            ]);
            exit;
        }

        // INSERT BODEGA
        $query = "INSERT INTO bodega (codigo, nombre, direccion, dotacion, estado_id)
                  VALUES ($1, $2, $3, $4, $5)
                  RETURNING id";

        $result = pg_query_params($conn, $query, [
            $codigo,
            $nombre,
            $direccion,
            $dotacion,
            $estado
        ]);

        if (!$result) {
            throw new Exception("Error al crear la bodega");
        }

        $row = pg_fetch_assoc($result);
        $bodega_id = $row['id'];

        // INSERT BODEGA_ENCARGADO
        $query2 = "INSERT INTO bodega_encargado (bodega_id, encargado_id)
                   VALUES ($1, $2)";

        $result2 = pg_query_params($conn, $query2, [
            $bodega_id,
            $encargado
        ]);

        if (!$result2) {
            throw new Exception("Error al asignar encargado");
        }

        echo json_encode([
            "status" => "ok",
            "mensaje" => "Bodega creada correctamente"
        ]);

    } catch (Exception $e) {

        echo json_encode([
            "status" => "error",
            "mensaje" => $e->getMessage()
        ]);
    }

} else {

    echo json_encode([
        "status" => "error",
        "mensaje" => "Faltan campos obligatorios"
    ]);
}
?>