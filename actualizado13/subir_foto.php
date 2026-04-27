<?php
include("conexion.php"); // Tu archivo de conexión a la DB
session_start();

if (!isset($_SESSION['id_usuario'])) {
    exit("No hay sesión activa");
}

if (isset($_FILES['foto'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nombre_archivo = $_FILES['foto']['name'];
    $ruta_temporal = $_FILES['foto']['tmp_name'];
    
    // Creamos un nombre único: perfil_1.jpg, perfil_2.png, etc.
    $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
    $nuevo_nombre = "perfil_" . $id_usuario . "." . $extension;
    $destino = "uploads/" . $nuevo_nombre;

    // Crear carpeta uploads si no existe
    if (!file_exists('uploads')) { mkdir('uploads', 0777, true); }

    if (move_uploaded_file($ruta_temporal, $destino)) {
        // ACTUALIZAMOS LA BASE DE DATOS
        $sql = "UPDATE usuario SET foto_perfil = '$destino' WHERE id_usuario = '$id_usuario'";
        if (mysqli_query($conexion, $sql)) {
            echo $destino; // Retornamos la nueva ruta al JavaScript
        }
    }
}
?>