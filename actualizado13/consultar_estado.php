<?php
session_start();
include("conexion.php");

$id_usuario = $_SESSION['id_usuario'] ?? 1;

$sql = "SELECT s.*, v.modelo, v.color, v.placas 
        FROM solicitud s
        LEFT JOIN vehiculos v ON s.id_conductor = v.id_usuario
        WHERE s.id_estudiante = '$id_usuario' 
        AND s.estado NOT IN ('finalizado', 'cancelado') 
        ORDER BY s.id_solicitud DESC LIMIT 1";

$result = $conexion->query($sql);
$row = $result ? $result->fetch_assoc() : null;

if(!$row){
    echo "Sin viaje activo";
    exit;
}

// Mapeo de mensajes según el estado
$mensajes = [
    'pendiente' => "Buscando conductor...",
    'aceptado'  => "🚗 Conductor asignado: {$row['modelo']} {$row['color']} ({$row['placas']})",
    'en_camino' => "🚕 Tu conductor va en camino",
    'llegó'     => "✅ El conductor ya llegó",
    'en_viaje'  => "🛣️ Estás en viaje",
    'finalizado'=> "Viaje terminado"
];

echo $mensajes[$row['estado']] ?? "Estado: " . $row['estado'];
?>