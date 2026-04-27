<?php
// 1. Evitamos que cualquier error de PHP ensucie la respuesta
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

include "conexion.php";

// Validar datos recibidos
if(!isset($_POST['id']) || !isset($_POST['estado'])){
    ob_end_clean();
    echo "error_datos";
    exit;
}

$id_solicitud = intval($_POST['id']);
$estado = $conexion->real_escape_string($_POST['estado']);

// 2. Actualizar estado en la solicitud
$sql = "UPDATE solicitud SET estado='$estado' WHERE id_solicitud=$id_solicitud";

if($conexion->query($sql) === TRUE){

    // 🔹 Notificaciones para el Estudiante (solo en estados clave)
    if($estado == 'llegó' || $estado == 'aceptado' || $estado == 'en_viaje'){
        
        // Consulta para obtener datos del conductor y el estudiante
        $sql2 = "SELECT s.id_estudiante, u.nombre AS conductor, v.modelo, v.color, v.placas
                 FROM solicitud s
                 JOIN conductores c ON s.id_conductor = c.id_conductor
                 JOIN usuario u ON c.id_usuario = u.id_usuario
                 LEFT JOIN vehiculos v ON v.id_conductor = c.id_conductor 
                 WHERE s.id_solicitud=$id_solicitud";

        $res = $conexion->query($sql2);
        if($res && $fila = $res->fetch_assoc()){
            $id_usuario_destino = $fila['id_estudiante'];
            $nombre_conductor = $conexion->real_escape_string($fila['conductor']);
            $modelo = $conexion->real_escape_string($fila['modelo'] ?? 'Vehículo UTranzit');
            $color = $conexion->real_escape_string($fila['color'] ?? 'N/A');
            $placas = $conexion->real_escape_string($fila['placas'] ?? 'S/P');

            $sql3 = "INSERT INTO notificaciones (id_solicitud, id_usuario, conductor, modelo, color, placas, estado)
                     VALUES ($id_solicitud, $id_usuario_destino, '$nombre_conductor', '$modelo', '$color', '$placas', '$estado')";
            $conexion->query($sql3);
        }
    }

    // 3. RESPUESTA LIMPIA
    ob_clean();
    echo "ok";
} else {
    ob_clean();
    echo "error_db";
}

$conexion->close();
exit;
?>