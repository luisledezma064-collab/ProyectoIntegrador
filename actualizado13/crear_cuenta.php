<?php
$conn = new mysqli("localhost","root","","utranzit");
if($conn->connect_error) die("Error en la base de datos");

$nombre = $_POST['usuario'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Verificar si ya existe el correo
$result = $conn->query("SELECT id_usuario FROM usuario WHERE correo='$correo'");
if($result->num_rows > 0){
    echo "Este correo ya está registrado ";
    exit();
}

// Hash de contraseña
$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

// Insertar usuario
$sql = "INSERT INTO usuario (nombre, correo, contrasena_hash) VALUES ('$nombre','$correo','$contrasena_hash')";
if($conn->query($sql)){
    echo "ok";
}else{
    echo "Error al crear la cuenta: ".$conn->error;
}

$conn->close();
?>