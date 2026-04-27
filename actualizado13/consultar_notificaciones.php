<?php
session_start();
include("conexion.php");

$id_usuario = $_SESSION['id_usuario'];

// Buscamos si hay viajes aceptados que el usuario no ha finalizado
$sql = "SELECT s.id_solicitud, s.estado, u.nombre as conductor 
        FROM solicitud s
        JOIN usuario u ON s.id_conductor = u.id_usuario
        WHERE s.id_estudiante = '$id_usuario' 
        AND s.estado = 'aceptado'
        ORDER BY s.id_solicitud DESC LIMIT 1";

$result = $conexion->query($sql);
$notificaciones = [];

if($row = $result->fetch_assoc()){
    $notificaciones[] = [
        'id_solicitud' => $row['id_solicitud'],
        'estado' => $row['estado'],
        'conductor' => $row['conductor']
    ];
}

header('Content-Type: application/json');
echo json_encode($notificaciones);
?>