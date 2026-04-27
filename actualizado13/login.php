<?php
// login_proceso.php (o el nombre de tu archivo de validación)
$conexion = mysqli_connect("localhost", "root", "", "utranzit");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

session_start();

// Limpiamos los datos para evitar inyecciones SQL
$correo   = mysqli_real_escape_string($conexion, $_POST['correo']);
$password = $_POST['contrasena']; 

// 1. Buscamos al usuario por correo
$consulta = "SELECT * FROM usuario WHERE correo='$correo'";
$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $datos = mysqli_fetch_assoc($resultado);

    // 2. Verificamos la contraseña con el hash de la BD
    if (password_verify($password, $datos['contrasena_hash'])) {
        
        // --- VALIDACIÓN DE CORREO INSTITUCIONAL (Excepto Admin) ---
        if ($datos['rol'] !== 'admin') {
            if (strtolower(substr($correo, -20)) !== "@virtual.utsc.edu.mx") {    
                header("Location: inicio_seccion.php?error=correo");
                exit();
            }
        }

        // --- SESIÓN DINÁMICA E INDEPENDIENTE ---
        // Aquí es donde sucede la magia: guardamos los datos reales del usuario
        $_SESSION['id_usuario'] = $datos['id_usuario']; // ID único de la tabla
        $_SESSION['nombre']     = $datos['nombre'];     // Su nombre real
        $_SESSION['rol']        = $datos['rol'];        // 'admin', 'conductor' o 'estudiante'

        // 3. Redirección inteligente según el ROL real de la base de datos
        switch ($datos['rol']) {
            case 'admin':
                header("Location: dashboard_admin.php");
                break;
            case 'conductor':
                header("Location: panel_conductor.php");
                break;
            case 'estudiante':
                header("Location: pantalla1.html");
                break;
            default:
                // Si hay un rol no definido, lo mandamos al inicio
                header("Location: inicio.html");
                break;
        }
        exit();

    } else {
        // Contraseña incorrecta
        header("Location: inicio_seccion.php?error=1");
        exit();
    }
} else {
    // Usuario no encontrado
    header("Location: inicio_seccion.php?error=1");
    exit();
}

mysqli_close($conexion);
?>