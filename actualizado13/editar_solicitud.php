<?php
include("conexion.php");
session_start();

$id = $_GET['id'];
$sql = "SELECT * FROM solicitud WHERE id_solicitud = $id";
$res = mysqli_query($conexion, $sql);
$data = mysqli_fetch_assoc($res);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_estado = $_POST['estado'];
    $update = "UPDATE solicitud SET estado = '$nuevo_estado' WHERE id_solicitud = $id";
    
    if (mysqli_query($conexion, $update)) {
        header("Location: dashboard_admin.php?msg=actualizado");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Solicitud - UTranzit</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        .form-edit { background: rgba(0,0,0,0.8); padding: 30px; border-radius: 15px; color: white; width: 300px; margin: 50px auto; }
        select, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; }
        button { background: #4ade80; border: none; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body class="fondo">
    <div class="form-edit">
        <h3>Actualizar Estado</h3>
        <p>Solicitud ID: <?php echo $id; ?></p>
        <form method="POST">
            <label>Estado del viaje:</label>
            <select name="estado">
                <option value="pendiente" <?php if($data['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="en curso" <?php if($data['estado'] == 'en curso') echo 'selected'; ?>>En curso</option>
                <option value="finalizado" <?php if($data['estado'] == 'finalizado') echo 'selected'; ?>>Finalizado</option>
            </select>
            <button type="submit">GUARDAR CAMBIOS</button>
            <br><br>
            <a href="dashboard_admin.php" style="color: gray; display: block; text-align: center;">Cancelar</a>
        </form>
    </div>
</body>
</html>