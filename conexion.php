<?php
$host = "127.0.0.1";
$db = "bodegas";
$user = "postgres";
$password = "123456";

$conn = pg_connect("host=$host port=5432 dbname=$db user=$user password=$password");

if (!$conn) {
    echo "Error de conexión";
    exit;
}
?>