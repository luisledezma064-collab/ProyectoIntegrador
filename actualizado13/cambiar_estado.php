<?php
session_start();
include("conexion.php");

if(!isset($_POST['id_solicitud']) || !isset($_POST['estado'])){
    echo "error";
    exit;
}

$id_solicitud = $_POST['id_solicitud'];
$estado = $_POST['estado'];

// Actualizar estado en la tabla solicitud
$stmt = $conexion->prepare("UPDATE solicitud SET estado = ? WHERE id_solicitud = ?");
$stmt->bind_param("si", $estado, $id_solicitud);

if($stmt->execute()){
    echo "ok";
} else {
    echo "error";
}

$stmt->close();
$conexion->close();
?>