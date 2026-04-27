
<?php
session_start();

// 🔥 SIMULACIÓN TEMPORAL
if(!isset($_SESSION['id_usuario'])){
    $_SESSION['id_usuario'] = 1;
}
include "conexion.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1️⃣ Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    die("Error: no hay sesión de usuario");
}

$id_estudiante = $_SESSION['id_usuario'];

// 2️⃣ Verificar que el usuario exista en la tabla 'usuario'
$sql_check = "SELECT id_usuario FROM usuario WHERE id_usuario = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("i", $id_estudiante);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    die("Error: usuario no encontrado en la base de datos");
}

// 3️⃣ Verificar que se reciba el id del punto de origen
if (!isset($_POST['id_punto'])) {
    die("Error: no se recibió id_punto");
}
$id_punto_origen = $_POST['id_punto'];
$id_punto_destino = 1; // o el que corresponda

// 4️⃣ Insertar la solicitud
$sql_insert = "INSERT INTO solicitud (estado, id_estudiante, id_punto_origen, id_punto_destino) 
               VALUES (?, ?, ?, ?)";
$stmt_insert = $conexion->prepare($sql_insert);
$estado = 'pendiente';
$stmt_insert->bind_param("siii", $estado, $id_estudiante, $id_punto_origen, $id_punto_destino);

if ($stmt_insert->execute()) {
    echo "Solicitud guardada correctamente ";
} else {
    echo "Error SQL: " . $stmt_insert->error;
}

$stmt_insert->close();
$stmt_check->close();
$conexion->close();
?>