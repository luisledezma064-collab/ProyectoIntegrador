<?php
include("conexion.php");
session_start();

// 1. Verificación de Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: inicio_seccion.php");
    exit();
}

// 2. Validar ID
if (isset($_GET['id'])) {
    $id_solicitud = intval($_GET['id']);

    // 3. Manejo de Integridad: Borrar primero el pago asociado (si existe)
    // Esto evita el error de "Foreign Key Constraint"
    mysqli_query($conexion, "DELETE FROM pago WHERE id_solicitud = $id_solicitud");

    // 4. Eliminar la solicitud
    $sql_delete = "DELETE FROM solicitud WHERE id_solicitud = $id_solicitud";

    if (mysqli_query($conexion, $sql_delete)) {
        // Éxito: Redirigir al dashboard
        header("Location: dashboard_admin.php?mensaje=eliminado");
    } else {
        // Error: Mostrar por qué no se pudo eliminar
        die("Error al eliminar la solicitud: " . mysqli_error($conexion));
    }
} else {
    header("Location: dashboard_admin.php");
}
exit();
?>