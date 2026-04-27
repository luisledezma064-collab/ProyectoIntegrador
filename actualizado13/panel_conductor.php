<?php
session_start();
$_SESSION['rol'] = 'conductor'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Conductores - UTranzit</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .navbar {
            background: #06220e; 
            padding: 12px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 50px;
            margin-bottom: 30px;
            border: 1px solid #1a3a22;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .brand {
            color: #10b981;
            font-weight: bold;
            font-size: 1.4rem;
            letter-spacing: 1px;
        }

        .nav-buttons {
            display: flex;
            gap: 12px;
        }

        .btn-nav {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
            margin: 5px 0;
        }

        .btn-nav:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: #10b981;
        }

        .btn-logout {
            border-color: rgba(239, 68, 68, 0.4);
        }

        .btn-logout:hover {
            background: #ef4444;
            border-color: #ef4444;
        }

        .main-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        #solicitudes-container {
            background-color: #1e1e1e;
            min-height: 300px;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border: 1px solid #2d2d2d;
        }

        .swal-custom-popup {
            border-radius: 20px !important;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="navbar">
            <div class="brand">UTSC</div>
            <div class="nav-buttons">
                <a href="avisos2.php" class="btn-nav">📢 Ver Avisos</a>
                <button onclick="confirmarRegreso()" class="btn-nav">← Inicio</button>
                <button onclick="confirmarSalida()" class="btn-nav btn-logout">✕ Cerrar Sesión</button>
            </div>
        </div>

        <h2>🚗 Panel de Conductores</h2>
        
        <div id="solicitudes-container">
            <p style="text-align:center; color:#888; margin-top:50px;">Buscando solicitudes disponibles...</p>
        </div>
    </div>

    <script>
        // 1. Cargar solicitudes automáticamente
        function cargarSolicitudes() {
            fetch('ver_solicitudes.php')
                .then(response => response.text())
                .then(data => {
                    const container = document.getElementById('solicitudes-container');
                    if (container.innerHTML !== data) {
                        container.innerHTML = data;
                    }
                })
                .catch(error => console.error('Error al cargar:', error));
        }

        // 2. Aceptar un viaje nuevo
        function aceptar(id_solicitud) {
            fetch('aceptar_solicitud.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id_solicitud=' + id_solicitud
            })
            .then(response => response.text())
            .then(res => {
                if(res.trim() === "ok"){
                    Swal.fire({
                        title: '¡Aceptada!',
                        text: 'Viaje asignado. ¡Ve por el estudiante!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#1e1e1e',
                        color: '#fff'
                    });
                }
                cargarSolicitudes();
            });
        }

        // 3. CAMBIAR ESTADO (En camino, Llegó, Iniciar Viaje, Finalizar)
    function cambiarEstado(id_solicitud, nuevo_estado) {
    console.log("Cambiando estado de:", id_solicitud, "a:", nuevo_estado);
    
    const datos = new URLSearchParams();
    datos.append('id', id_solicitud);
    datos.append('estado', nuevo_estado);

    fetch('actualizar_estado.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: datos
    })
    .then(response => response.text())
    .then(res => {
        console.log("Respuesta servidor:", res);
        if(res.trim() === "ok"){
            // Si funciona, refrescamos los botones de inmediato
            cargarSolicitudes();
        } else {
            // Si el PHP manda error, igual refrescamos para ver qué pasó
            cargarSolicitudes();
        }
    })
    .catch(error => console.error('Error en fetch:', error));
}

        // 4. Utilidades de Navegación
        function confirmarRegreso() {
            window.location.href = 'inicio.html';
        }

        function confirmarSalida() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, salir',
                background: '#1e1e1e',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = 'logout.php';
            });
        }

        // Configuración de intervalos
        setInterval(cargarSolicitudes, 3000);
        cargarSolicitudes();
        localStorage.setItem('ultimo_inicio', 'panel_conductor.php');
    </script>
</body>
</html>