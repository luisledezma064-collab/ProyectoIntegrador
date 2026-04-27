<?php
// Reportar errores de MySQLi como excepciones
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli("localhost", "root", "", "utranzit");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $curp        = $conn->real_escape_string($_POST['curp']);
        $licencia    = $conn->real_escape_string($_POST['licencia']);
        $numero_ine  = $conn->real_escape_string($_POST['numero_ine']);
        $placas      = $conn->real_escape_string($_POST['placas']);
        $modelo      = $conn->real_escape_string($_POST['modelo']);
        $color       = $conn->real_escape_string($_POST['color']);
        $asientos    = (int)$_POST['asientos'];

        // Procesar foto
        $foto_ine = $_FILES['foto_ine'];
        $nombre_archivo = time() . "_" . basename($foto_ine['name']);
        $ruta_destino = "ine/" . $nombre_archivo;
        move_uploaded_file($foto_ine['tmp_name'], $ruta_destino);

        // Iniciar transacción
        $conn->begin_transaction();

        // A. Crear Usuario (Base de la cuenta)
        $pass_hash = password_hash("123456", PASSWORD_DEFAULT);
        $sql_user = "INSERT INTO usuario (nombre, correo, contrasena_hash) 
                     VALUES ('Nuevo Conductor', 'c_".uniqid()."@utranzit.com', '$pass_hash')";
        $conn->query($sql_user);
        $id_usuario_nuevo = $conn->insert_id;

        // B. Insertar Conductor 
        // Relacionamos este conductor con el usuario recién creado
        $sql_cond = "INSERT INTO conductores (id_usuario, licencia, numero_ine, curp, foto_ine) 
                     VALUES ('$id_usuario_nuevo', '$licencia', '$numero_ine', '$curp', '$ruta_destino')";
        $conn->query($sql_cond);
        
        // ¡PASO CRUCIAL! Obtenemos el ID del CONDUCTOR (no del usuario) para el vehículo
        $id_conductor_nuevo = $conn->insert_id;

        // C. Insertar Vehículo 
        // Usamos $id_conductor_nuevo para satisfacer la llave foránea (CONSTRAINT `vehiculos_ibfk_1`)
        $sql_veh = "INSERT INTO vehiculos (placas, modelo, color, asientos, id_usuario) 
                    VALUES ('$placas', '$modelo', '$color', $asientos, '$id_conductor_nuevo')";
        $conn->query($sql_veh);

        // Si todo salió bien, guardamos los cambios
        $conn->commit();
        echo "ok";
    }
} catch (mysqli_sql_exception $e) {
    // Si algo falla, revertimos todo lo que se alcanzó a insertar
    if (isset($conn)) $conn->rollback();
    
    // Borrar la foto subida si el registro falló para no dejar basura
    if (isset($ruta_destino) && file_exists($ruta_destino)) {
        unlink($ruta_destino);
    }

    if ($e->getCode() == 1062) {
        echo "el_ine_ya_existe";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>