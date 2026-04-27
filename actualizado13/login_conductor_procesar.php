<?php

$conn = new mysqli("localhost","root","","utranzit");

if($conn->connect_error){
    die("Error de conexión");
}

session_start();

$numero_ine = $conn->real_escape_string($_POST['numero_ine']);
$licencia = $conn->real_escape_string($_POST['licencia']);

// Buscar conductor
$sql = "SELECT * FROM conductores 
        WHERE numero_ine='$numero_ine' 
        AND licencia='$licencia'";

$result = $conn->query($sql);

if($result->num_rows > 0){

    $conductor = $result->fetch_assoc();

    $_SESSION['id_usuario'] = $conductor['id_usuario'];
    $_SESSION['tipo'] = "conductor";

    header("Location: panel_conductor.php");
    exit();

}else{

    echo "<script>
    alert('INE o licencia incorrectos');
    window.history.back();
    </script>";

}

$conn->close();

?>