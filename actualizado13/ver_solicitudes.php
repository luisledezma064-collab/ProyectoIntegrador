<?php
session_start();
include("conexion.php");

// 1. VALIDACIÓN DE SESIÓN: Evita que el SQL truene si el usuario no está logueado
if (!isset($_SESSION['id_usuario'])) {
    echo "<p style='color: #ef4444; text-align: center; margin-top: 20px;'>
            ⚠️ Sesión expirada. Por favor, inicia sesión nuevamente.
          </p>";
    exit; // Detenemos el script para evitar errores de "Undefined array key"
}

$id_usuario_sistema = $_SESSION['id_usuario'];

// 2. OBTENER ID DE CONDUCTOR: Con validación de existencia
$consulta_cond = "SELECT id_conductor FROM conductores WHERE id_usuario = $id_usuario_sistema";
$res_cond = $conexion->query($consulta_cond);

if (!$res_cond || $res_cond->num_rows == 0) {
    echo "<p style='color: #888; text-align: center;'>No se encontró perfil de conductor activo.</p>";
    exit;
}

$fila_cond = $res_cond->fetch_assoc();
$id_conductor_real = $fila_cond['id_conductor'];

// 3. TRAER SOLICITUDES: Con el JOIN corregido para mostrar al estudiante real
$sql = "SELECT s.*, 
               u_estudiante.nombre AS nombre_pasajero, 
               p.nombre AS parada_nombre
        FROM solicitud s
        -- Unimos con la tabla usuario usando el ID del estudiante que pidió el viaje
        JOIN usuario u_estudiante ON s.id_estudiante = u_estudiante.id_usuario
        JOIN punto_encuentro p ON s.id_punto_origen = p.id_punto
        WHERE (s.estado = 'pendiente' OR s.id_conductor = $id_conductor_real)
          AND s.estado != 'finalizado'
        ORDER BY s.id_solicitud DESC";

$resultado = $conexion->query($sql);

if($resultado->num_rows > 0){
    while($fila = $resultado->fetch_assoc()){
        $id = $fila['id_solicitud'];
        
        // INICIO DE LA TARJETA
        echo "<div class='solicitud' style='background: #1a1a1a; border: 1px solid #333; padding: 20px; margin-bottom: 20px; border-radius: 15px;'>";
        
        echo "<h3 style='margin-top:0;'>📍 Solicitud #$id</h3>";
        echo "<p><strong>Estudiante:</strong> " . $fila['nombre_pasajero'] . "</p>"; // Muestra el nombre del pasajero, no el tuyo
        echo "<p><strong>Punto:</strong> " . $fila['parada_nombre'] . "</p>";
        echo "<p><strong>Estado:</strong> <span style='color: #10b981; font-weight:bold;'>" . $fila['estado'] . "</span></p>";
        
        echo "<hr style='border: 0; border-top: 1px solid #444; margin: 15px 0;'>";

        // --- BOTONES DE ACCIÓN (Dentro del div de la tarjeta) ---

        if($fila['estado'] == 'pendiente'){
            echo "<button class='btn-nav' style='width:100%;' onclick='aceptar($id)'>✅ Aceptar viaje</button>";
        }

        if($fila['estado'] == 'aceptado'){
            echo "<button class='btn-nav' style='width:100%; background: #3b82f6;' onclick=\"cambiarEstado($id, 'en_camino')\">🚗 En camino</button>";
        }

        if($fila['estado'] == 'en_camino'){
            echo "<button class='btn-nav' style='width:100%; background: #f59e0b; color: #000;' onclick=\"cambiarEstado($id, 'llegó')\">📍 Ya llegué</button>";
        }

        if($fila['estado'] == 'llegó'){
            echo "<button class='btn-nav' style='width:100%; background: #10b981;' onclick=\"cambiarEstado($id, 'en_viaje')\">🟢 Iniciar viaje</button>";
        }

        if($fila['estado'] == 'en_viaje'){
            echo "<button class='btn-nav' style='width:100%; background: #ef4444;' onclick=\"cambiarEstado($id, 'finalizado')\">🏁 Finalizar viaje</button>";
        }

        echo "</div>"; // CIERRE DE LA TARJETA
    }
} else {
    echo "<p style='color: #888; text-align: center; margin-top: 20px;'>No hay solicitudes disponibles por el momento.</p>";
}
?>