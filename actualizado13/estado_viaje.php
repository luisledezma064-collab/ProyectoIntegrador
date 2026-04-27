<?php
session_start();
include("conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT s.*, v.modelo, v.color, v.placas 
        FROM solicitud s
        LEFT JOIN vehiculos v ON s.id_conductor = v.id_usuario
        WHERE s.id_estudiante = '$id_usuario'
        ORDER BY s.id_solicitud DESC LIMIT 1";

$result = $conexion->query($sql);

if(!$result){
    die("Error en la consulta: " . $conexion->error);
}

$row = $result->fetch_assoc();

if(!$row){
    echo "Sin viaje activo";
    exit;
}

// 🔥 ESTADOS
if($row['estado'] == 'pendiente'){
    echo " Buscando conductor...";
}
elseif($row['estado'] == 'aceptado'){
echo " Conductor asignado<br>
      Modelo: {$row['modelo']}<br>
      Color: {$row['color']}<br>
      Placas: {$row['placas']}";
}
elseif($row['estado'] == 'en_camino'){
    echo " Tu conductor va en camino";
}
elseif($row['estado'] == 'llegó'){
    echo " Tu conductor ya llegó";
}
elseif($row['estado'] == 'en_viaje'){
    echo " Estás en viaje";
}
elseif($row['estado'] == 'finalizado'){
    echo " Viaje terminado";
}
?>