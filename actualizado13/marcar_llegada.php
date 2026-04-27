<?php
session_start();
include("conexion.php");

if(!isset($_POST['id_solicitud'])){
    die("Error: no se recibió id_solicitud");
}

$id_solicitud = $_POST['id_solicitud'];

// 🔥 Actualizar estado a 'llegó' y notificado = 0
$sql = "UPDATE solicitud SET estado='llegó', notificado=0 WHERE id_solicitud=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_solicitud);

if($stmt->execute()){
    echo "Estado actualizado a 'llegó'";
}else{
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>  