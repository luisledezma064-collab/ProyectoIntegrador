<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTranzit - Inicio</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body class="fondo">

    <div class="c1">
        
        <div class="linea"></div>

        <h1 class="h2">UTranzit</h1>
        <h2 class="h3">CREA TU CUENTA Y EMPIEZA TU VIAJE</h2>

    

        <section class="login-section">
    
            <div class="c2"></div>

    <img class="perro" src="perroverde.jpg">

<form class="formulario" action="login.php" method="POST">

<?php
    if (isset($_GET['error'])) {
        // Cambia la línea de 'color' aquí abajo:
        echo '<p style="color: #B91C1C; 
                       background: #fee2e2; 
                       padding: 10px; 
                       position: absolute;
                       top: -24px;
                       left: 98px;
                       border-radius: 5px; 
                       font-size: 12px; 
                       text-align: center; 
                       font-family: monospace;
                       margin-bottom: 15px; 
                       border: 1px solid #FCA5A5;">
                 ❌Usuario, correo o contraseña incorrectos.
              </p>';
    }
?>
    
    <div class="input-group">
        <input type="text" name="usuario" required placeholder=" ">
        <label class="label-flotante">Nombre de Usuario</label>
        <span class="barra-brillo"></span>
    </div>

    <div class="input-group">
        <input type="email" name="correo" required placeholder=" ">
        <label class="label-flotante">Correo</label>
        <span class="barra-brillo"></span>
    </div>

    <div class="input-group">
        <input type="password" id="contrasena" name="contrasena" required placeholder=" ">
        <label class="label-flotante">Contraseña</label>
        <button type="button" class="btn-ver" onclick="mostrarContrasena()">👁</button>
        <span class="barra-brillo"></span>
    </div>

    <button type="submit" class="btn-moderno">
        <span>INICIO DE SECCION</span>
    </button><BR></BR>
    <div class="br">
<br > SI ERES NUEVO INGRESA AQUI<br></div>
<BR></BR>

<a href="inicio.html">
    <button type="button" class="btn-moderno">
        <span>CREAR CUENTA</span>
    </button></a>


<a href="registro_conductor.php">
    <button type="button" class="btn-moderno">
        <span>INGRESA COMO CONDUCTOR</span>
    </button>
</a>

           <div class="redes-sociales">
    
    <div class="iconos-redes">
        <a href="https://www.facebook.com/UTSCNL?locale=es_LA" class="icono-link">
            <img style="border-radius: 5px;" src="fa.webp" alt="Facebook">
        </a>
        <a href="https://www.instagram.com/utsantacatarina/" class="icono-link">
            <img style="border-radius: 5px;" src="ig.png" alt="Google">
        </a>
        <a href="https://www.tiktok.com/@correcaminosutsc" class="icono-link">
            <img style="border-radius: 5px;" src="tt.png" alt="Twitter">
        </a>
    </div>
    </form>
</section>
    </div>

    <script>
    function mostrarContrasena() {
        var input = document.getElementById("contrasena");
        input.type = (input.type === "password") ? "text" : "password";
    }
    </script>

 
</div>
</body>
</html>