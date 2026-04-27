<?php
session_start();

$conn = new mysqli("localhost","root","","utranzit");

if($conn->connect_error){
    die("Error conexión");
}

if(!isset($_SESSION['id_usuario'])){
    echo "no_session";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

if(isset($_POST['id'])){

$id = intval($_POST['id']);

$stmt = $conn->prepare("
DELETE FROM metodo_pago
WHERE id_metodo = ?
AND id_usuario = ?
");

$stmt->bind_param("ii",$id,$id_usuario);

if($stmt->execute()){
    echo "ok";
}else{
    echo "error";
}

$stmt->close();
}

$conn->close();
?>