<?php
include("conexion.php");
session_start();

// 1. Verificación de Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: inicio_seccion.php");
    exit();
}

// 2. Validar que el ID llegue por la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<h2 style='color:red;'>Error: No se recibió el ID del usuario.</h2><a href='dashboard_admin.php'>Regresar</a>");
}

$id = intval($_GET['id']);

// 3. Consulta Blindada (Usamos LEFT JOIN para que NO falle si no hay vehículo)
$sql = "SELECT u.nombre, c.id_conductor, c.licencia, v.modelo, v.placas, v.color 
        FROM usuario u 
        INNER JOIN conductores c ON u.id_usuario = c.id_usuario 
        LEFT JOIN vehiculos v ON c.id_conductor = v.id_usuario 
        WHERE u.id_usuario = $id";

$res = mysqli_query($conexion, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    // Si llegas aquí, es porque el ID no existe en la tabla conductores
    die("<h2 style='color:orange;'>Aviso: El usuario ID $id no está registrado como conductor.</h2><a href='dashboard_admin.php'>Regresar</a>");
}

$data = mysqli_fetch_assoc($res);
$id_conductor_real = $data['id_conductor'];

// 4. Procesar el Formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $licencia = mysqli_real_escape_string($conexion, $_POST['licencia']);
    $modelo = mysqli_real_escape_string($conexion, $_POST['modelo']);
    $placas = mysqli_real_escape_string($conexion, $_POST['placas']);
    $color = mysqli_real_escape_string($conexion, $_POST['color']);

    // Iniciar una pequeña transacción manual para asegurar que todo se guarde
    $error = false;

    if (!mysqli_query($conexion, "UPDATE usuario SET nombre='$nombre' WHERE id_usuario=$id")) $error = true;
    if (!mysqli_query($conexion, "UPDATE conductores SET licencia='$licencia' WHERE id_usuario=$id")) $error = true;

    // Lógica para el vehículo: ¿Existe ya uno?
    $check_v = mysqli_query($conexion, "SELECT id_vehiculo FROM vehiculos WHERE id_usuario=$id_conductor_real");
    
    if (mysqli_num_rows($check_v) > 0) {
        if (!mysqli_query($conexion, "UPDATE vehiculos SET modelo='$modelo', placas='$placas', color='$color' WHERE id_usuario=$id_conductor_real")) $error = true;
    } else {
        // Si no tenía vehículo, lo creamos
        if (!mysqli_query($conexion, "INSERT INTO vehiculos (placas, modelo, color, asientos, id_usuario) VALUES ('$placas', '$modelo', '$color', 4, $id_conductor_real)")) $error = true;
    }

    if ($error) {
        echo "<script>alert('Error al guardar algunos datos: " . mysqli_error($conexion) . "');</script>";
    } else {
        header("Location: dashboard_admin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Conductor - UTranzit</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        .c1 { min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; font-family: sans-serif; }
        .glass-form { background: #000; padding: 30px; border-radius: 20px; border: 2px solid #4ade80; width: 100%; max-width: 500px; color: white; }
        input { width: 100%; padding: 12px; margin: 10px 0 20px 0; background: #111; border: 1px solid #4ade80; color: white; border-radius: 8px; box-sizing: border-box; }
        .btn-save { width: 100%; padding: 15px; background: #4ade80; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; color: black; }
        .btn-back { display: block; text-align: center; margin-top: 15px; color: #94a3b8; text-decoration: none; }
    </style>
</head>
<body style="background-color: #0a0a0a;">
    <div class="c1">
        <div class="glass-form">
            <h2 style="color: #4ade80; margin-top: 0;">✏️ Editar Conductor</h2>
            <form method="POST">
                <label>Nombre Completo:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($data['nombre']); ?>" required>

                <label>Licencia:</label>
                <input type="text" name="licencia" value="<?php echo htmlspecialchars($data['licencia']); ?>" required>

                <label>Modelo del Vehículo:</label>
                <input type="text" name="modelo" value="<?php echo htmlspecialchars($data['modelo'] ?? ''); ?>" placeholder="Ej: Nissan Sentra">

                <label>Placas:</label>
                <input type="text" name="placas" value="<?php echo htmlspecialchars($data['placas'] ?? ''); ?>" placeholder="Ej: ABC-123">

                <label>Color:</label>
                <input type="text" name="color" value="<?php echo htmlspecialchars($data['color'] ?? ''); ?>" placeholder="Ej: Rojo">

                <button type="submit" class="btn-save">GUARDAR CAMBIOS</button>
                <a href="dashboard_admin.php" class="btn-back">Cancelar y Volver</a>
            </form>
        </div>
    </div>
</body>
</html>