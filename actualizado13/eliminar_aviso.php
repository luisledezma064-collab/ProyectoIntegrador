<?php
// 1. Conexión a tu base de datos
$conexion = mysqli_connect("localhost", "root", "", "utranzit");

// 2. Verificar si recibimos un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 3. Sentencia para eliminar
    $sql = "DELETE FROM avisos WHERE id = $id";

    if (mysqli_query($conexion, $sql)) {
        // Regresar a la página de avisos después de borrar
        header("Location: avisos.php?deleted=1");
    } else {
        echo "Error al eliminar: " . mysqli_error($conexion);
    }
}
?>