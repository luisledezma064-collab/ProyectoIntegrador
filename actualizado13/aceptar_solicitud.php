<?php
session_start();
include("conexion.php");

if(!isset($_SESSION['id_usuario'])){
    echo "error_sesion";
    exit;
}

$id_solicitud = $_POST['id_solicitud'];
$id_usuario_sesion = $_SESSION['id_usuario']; 

// 1. Primero obtenemos el ID de la tabla 'conductores' usando el ID de usuario
$sql_get_cond = "SELECT id_conductor FROM conductores WHERE id_usuario = ?";
$stmt_cond = $conexion->prepare($sql_get_cond);
$stmt_cond->bind_param("i", $id_usuario_sesion);
$stmt_cond->execute();
$res_cond = $stmt_cond->get_result();

if($fila = $res_cond->fetch_assoc()){
    $id_conductor_real = $fila['id_conductor'];
    
    // 2. Ahora sí, actualizamos la solicitud con el ID de CONDUCTOR real
    $sql = "UPDATE solicitud 
            SET estado='aceptado', id_conductor=? 
            WHERE id_solicitud=? AND estado='pendiente'";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id_conductor_real, $id_solicitud);

    if($stmt->execute()){
        if($stmt->affected_rows > 0){
            echo "ok"; 
        } else {
            echo "ocupado"; 
        }
    } else {
        echo "error_ejecucion";
    }
} else {
    // Si el usuario logueado no está registrado como conductor
    echo "no_es_conductor";
}
?>