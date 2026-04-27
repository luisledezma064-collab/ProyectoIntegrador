<?php

include "conexion.php";

session_start();

$id_conductor = $_SESSION['id_usuario'];

$id_solicitud = $_POST['id'];

$sql = "UPDATE solicitud
SET estado='aceptado', id_conductor=$id_conductor
WHERE id_solicitud=$id_solicitud";

$conn->query($sql);

echo "ok";

?>