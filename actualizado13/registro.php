<?php
$conexion = mysqli_connect("localhost", "root", "", "utranzit");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$usuario  = $_POST['usuario'];
$correo   = $_POST['correo'];
$password = $_POST['contrasena'];

// Validar correo institucional
if (substr($correo, -20) !== "@virtual.utsc.edu.mx"){
    echo "<script>
    alert('Debes usar tu correo institucional UTSC');
    window.location.href='inicio.html';
    </script>";
    exit();
}

// 1. IMPORTANTE: Escapar los datos para evitar errores por caracteres especiales
$usuario_esc = mysqli_real_escape_string($conexion, $usuario);
$correo_esc  = mysqli_real_escape_string($conexion, $correo);
$pass_esc    = mysqli_real_escape_string($conexion, $password);

$sql = "INSERT INTO usuario (nombre, correo, contrasena_hash) 
        VALUES ('$usuario_esc', '$correo_esc', '$pass_esc')";

// 2. CORRECCIÓN: Sin comillas en las variables de la función
if (mysqli_query($conexion, $sql)) {
    echo "<script>
            alert('¡Registro exitoso!');
            window.location.href='pantalla1.html';
          </script>";
} else {
    echo "Error en la base de datos: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>