<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: inicio_seccion.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_usuario = intval($_GET['id']);

    // 1. Obtener el id_conductor real antes de borrar nada
    $res = mysqli_query($conexion, "SELECT id_conductor FROM conductores WHERE id_usuario = $id_usuario");
    $data = mysqli_fetch_assoc($res);
    
    if ($data) {
        $id_conductor = $data['id_conductor'];

        // --- ORDEN DE BORRADO PARA EVITAR EL FOREIGN KEY ERROR ---
        
        // A. Borrar vehículo asociado al conductor
        mysqli_query($conexion, "DELETE FROM vehiculos WHERE id_usuario = $id_conductor");

        // B. Borrar pagos de los viajes de este conductor
        mysqli_query($conexion, "DELETE FROM pago WHERE id_solicitud IN (SELECT id_solicitud FROM solicitud WHERE id_conductor = $id_conductor)");

        // C. Borrar viajes (solicitudes) donde él fue el conductor
        mysqli_query($conexion, "DELETE FROM solicitud WHERE id_conductor = $id_conductor");

        // D. Borrar el registro de la tabla conductores
        mysqli_query($conexion, "DELETE FROM conductores WHERE id_usuario = $id_usuario");

        // E. Finalmente, borrar el usuario base
        if (mysqli_query($conexion, "DELETE FROM usuario WHERE id_usuario = $id_usuario")) {
            header("Location: dashboard_admin.php?msj=eliminado");
        } else {
            die("Error final: " . mysqli_error($conexion));
        }
    } else {
        header("Location: dashboard_admin.php?error=no_encontrado");
    }
}
exit();
?>