<?php
// Configuración de conexión (ajusta a tus datos)
$conexion = mysqli_connect("localhost", "root", "", "utranzit");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $tipo = $_POST['tipo'];
    $fecha = date("d M, Y");

    $sql = "INSERT INTO avisos (titulo, contenido, tipo, fecha) 
            VALUES ('$titulo', '$contenido', '$tipo', '$fecha')";

    if (mysqli_query($conexion, $sql)) {
        header("Location: avisos.php?success=1");
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>