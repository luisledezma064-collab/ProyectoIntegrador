<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost","root","","utranzit");

if($conn->connect_error){
    die("Error conexión");
}

if(!isset($_SESSION['id_usuario'])){
    echo json_encode([]);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$stmt = $conn->prepare("
SELECT id_metodo, tipo, numero_tarjeta, titular
FROM metodo_pago
WHERE id_usuario = ?
");

$stmt->bind_param("i",$id_usuario);
$stmt->execute();

$result = $stmt->get_result();

$tarjetas = [];

while($row = $result->fetch_assoc()){
    $tarjetas[] = $row;
}

echo json_encode($tarjetas);

$stmt->close();
$conn->close();
?>