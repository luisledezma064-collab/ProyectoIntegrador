<?php
include("conexion.php");
session_start();

// 1. Seguridad: Esto se queda igual
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: inicio_seccion.php");
    exit();
}

// --- AQUÍ COPIAS Y PEGAS EL NUEVO CÓDIGO ---

// CONSULTA 1: Conductores y Vehículos (CORREGIDA)
$sql_conductores = "SELECT u.id_usuario, u.nombre, c.licencia, v.modelo, v.placas, v.color 
                    FROM conductores c
                    JOIN usuario u ON c.id_usuario = u.id_usuario
                    LEFT JOIN vehiculos v ON c.id_conductor = v.id_usuario"; 
$res_conductores = mysqli_query($conexion, $sql_conductores);

// CONSULTA 2: Solicitudes de viaje (CORREGIDA)
$sql_viajes = "SELECT s.id_solicitud, u.nombre AS estudiante, pe1.nombre AS origen, pe2.nombre AS destino, s.estado 
               FROM solicitud s
               JOIN usuario u ON s.id_estudiante = u.id_usuario
               JOIN punto_encuentro pe1 ON s.id_punto_origen = pe1.id_punto
               JOIN punto_encuentro pe2 ON s.id_punto_destino = pe2.id_punto";
$res_viajes = mysqli_query($conexion, $sql_viajes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTranzit - Panel Admin</title>
    <link rel="stylesheet" href="style2.css"> 
    <style>
        /* Liberar la pantalla del efecto atrapado */
        html, body {
            height: auto !important;
            min-height: 100vh;
            overflow-y: auto !important;
            margin: 0;
            padding: 0;
            background-attachment: fixed;
        }

        .c1 { 
            height: 50%; 
            min-height: 100vh; 
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
            width: 100%;
        }

        .h2-main { 
            text-align: center; 
            color: white; 
            font-size: 2.2rem; 
            margin-bottom: 30px;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
        }

        .glass-container {
            width: 95%; 
            max-width: 1300px; 
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 20px;
            border: 1px solid rgba(74, 222, 128, 0.3);
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            overflow-x: auto;
        }

        .header-seccion {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        h2 { color: #4ade80; margin: 0; font-size: 1.6rem; }

        .tabla-admin { 
            width: 100%; 
            border-collapse: collapse; 
            color: white; 
            margin-top: 10px;
        }

        .tabla-admin th { 
            background: #064e3b; 
            color: #4ade80; 
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #4ade80;
            white-space: nowrap;
            h
        }

        .tabla-admin td { 
            padding: 12px; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
        }

        .btn-add {
            background: #4ade80;
            color: black;
            padding: 8px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-add:hover { background: #22c55e; transform: scale(1.05); }

        .btn-edit { color: #60a5fa; text-decoration: none; font-weight: bold; margin-right: 15px; font-size: 1.1rem; }
        .btn-delete { color: #f87171; text-decoration: none; font-weight: bold; font-size: 1.1rem; }

        .status-pill {
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .btn-cerrar {
            padding: 12px 40px;
            background: #4ade80;
            color: black;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 40px;
            transition: 0.3s;
        }
    </style>
</head>
<body class="fondo">
    <div class="c1">
        <h1 class="h2-main">PANEL DE ADMINISTRACIÓN</h1>
         

        <div class="glass-container">
            
            <div class="header-seccion">
                <h2>🚗 Conductores y Vehículos</h2>
                <a href="registro_conductor.php" class="btn-add">+ Registrar Conductor</a>
            </div>
            <table class="tabla-admin">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Licencia</th>
                        <th>Vehículo</th>
                        <th>Placas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($c = mysqli_fetch_assoc($res_conductores)) { ?>
                    <tr>
                        <td><?php echo $c['nombre']; ?></td>
                        <td><?php echo $c['licencia']; ?></td>
                        <td><?php echo $c['modelo'] . " (" . $c['color'] . ")"; ?></td>
                        <td><?php echo $c['placas']; ?></td>
                        <td>
                            <a href="editar_conductor.php?id=<?php echo $c['id_usuario']; ?>" class="btn-edit" title="Editar">✏️</a>
                            <a href="eliminar_conductor.php?id=<?php echo $c['id_usuario']; ?>" 
                               onclick="return confirm('¿Eliminar a este conductor?')" class="btn-delete" title="Borrar">🗑️</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="glass-container">
            <div class="header-seccion">
                <h2>📍 Gestión de Solicitudes</h2>
            </div>
            <table class="tabla-admin">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estudiante</th>
                        <th>Origen ➜ Destino</th>
                        <th>Estado</th>
                        <th>Acciones</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php while($v = mysqli_fetch_assoc($res_viajes)) { ?>
                    <tr>
                        <td><?php echo $v['id_solicitud']; ?></td>
                        <td><?php echo $v['estudiante']; ?></td>
                        <td><?php echo $v['origen'] . " ➜ " . $v['destino']; ?></td>
                        <td>
                            <span class="status-pill" style="border: 1px solid <?php echo ($v['estado'] == 'finalizado') ? '#4ade80' : '#fbbf24'; ?>; color: <?php echo ($v['estado'] == 'finalizado') ? '#4ade80' : '#fbbf24'; ?>;">
                                <?php echo $v['estado']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="editar_solicitud.php?id=<?php echo $v['id_solicitud']; ?>" class="btn-edit">✏️</a>
                            <a href="eliminar_solicitud.php?id=<?php echo $v['id_solicitud']; ?>" 
                               onclick="return confirm('¿Borrar esta solicitud?')" class="btn-delete">🗑️</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <a href="inicio_seccion.php" class="btn-cerrar">CERRAR SESIÓN</a>
    </div>
</body>
</html>