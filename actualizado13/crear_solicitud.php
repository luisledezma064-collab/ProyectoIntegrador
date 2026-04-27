<?php

include "conexion.php";

session_start();

$id_estudiante = $_SESSION['id_usuario'];

$parada = $_POST['parada'];

$sql_parada = "SELECT id_punto FROM punto_encuentro WHERE nombre='$parada'";
$result = $conn->query($sql_parada);

$row = $result->fetch_assoc();

$id_parada = $row['id_punto'];

$id_destino = 1;

$sql = "INSERT INTO solicitud
(estado,id_estudiante,id_punto_origen,id_punto_destino)
VALUES
('pendiente',$id_estudiante,$id_parada,$id_destino)";

$conn->query($sql);

echo "ok";

?>