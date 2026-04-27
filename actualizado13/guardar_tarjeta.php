<?php
session_start();

$conn = new mysqli("localhost","root","","utranzit");

if($conn->connect_error){
    die("Error de conexión");
}

if(!isset($_SESSION['id_usuario'])){
    echo "no_session";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$numero = $_POST['numero_tarjeta'] ?? "";
$titular = $_POST['titular'] ?? "";
$tipo = $_POST['tipo'] ?? "desconocido";
$token = $_POST['token'] ?? "testtoken";

/* limpiar número */
$numero = str_replace(" ","",$numero);

/* validar */
if(strlen($numero) < 12){
    echo "numero_invalido";
    exit();
}

/* obtener últimos 4 */
$ultimos4 = substr($numero,-4);

/* insertar */
$stmt = $conn->prepare("
INSERT INTO metodo_pago
(tipo, numero_tarjeta, titular, id_usuario, token)
VALUES (?,?,?,?,?)
");

$stmt->bind_param(
"sssis",
$tipo,
$ultimos4,
$titular,
$id_usuario,
$token
);

if($stmt->execute()){
    echo "ok";
}else{
    echo "error";
}

$stmt->close();
$conn->close();
?>