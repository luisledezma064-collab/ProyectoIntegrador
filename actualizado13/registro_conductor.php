<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTranzit - Registro Conductor</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* RESET */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* FONDO */
        .fondo {
            background-image: url(hola.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        /* CAJA CRISTAL */
        .c1 {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.2);
            width: 900px;
            max-width: 95%;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 25px 45px rgba(0,0,0,0.5);
        }

        .h2 { font-family: Impact, sans-serif; font-size: 42px; color: #fff; text-align: center; }
        .h3 { font-family: monospace; font-size: 16px; color: #fff; text-align: center; margin-bottom: 20px; }

        /* FORMULARIO 2 COLUMNAS */
        .formulario {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 30px;
        }

        .input-group { position: relative; width: 100%; }

        .input-group input {
            width: 100%;
            padding: 15px 10px;
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 8px;
            outline: none;
            color: #000;
            font-size: 14px;
        }

        .input-group label {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            font-size: 13px;
            color: rgba(0,0,0,0.6);
            transition: .3s;
            pointer-events: none;
        }

        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
            top: -10px;
            font-size: 11px;
            color: #fff; /* Cambiado a blanco para que resalte en el diseño oscuro */
        }

        .barra-brillo {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0%;
            height: 2px;
            background: #fff;
            transition: .3s;
        }

        .input-group input:focus ~ .barra-brillo { width: 100%; }

        /* BOTONES */
        .btn-moderno, .btn-conductor {
            padding: 12px 25px;
            margin: 10px 0;
            border: none;
            border-radius: 50px;
            font-weight: bold;
            letter-spacing: 2px;
            cursor: pointer;
            color: #fff;
            background: linear-gradient(90deg,#000,#333);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-conductor {
            grid-column: span 2;
            width: 280px;
            justify-self: center;
        }

        .top-actions {
            grid-column: span 2;
            text-align: center;
            margin-bottom: 10px;
            color: #fff;
        }

        .input-group-file {
            grid-column: span 2;
            color: white;
            text-align: center;
            background: rgba(255,255,255,0.05);
            padding: 15px;
            border-radius: 10px;
            border: 1px dashed rgba(255,255,255,0.3);
        }

        @media (max-width: 768px) {
            .formulario { grid-template-columns: 1fr; }
            .btn-conductor, .input-group-file { grid-column: span 1; }
        }
    </style>
</head>
<body class="fondo">

<div class="c1">
    <h1 class="h2">UTranzit</h1>
    <h2 class="h3">REGISTRO PARA CONDUCTORES</h2>

    <form id="register-form" class="formulario" enctype="multipart/form-data">
        
        <div class="top-actions">
            ¿Ya tienes cuenta? <br>
            <a href="login_conductor.php" class="btn-moderno">INICIAR SESIÓN</a>
            <br>
            <a href="inicio.html" style="color: #fff; font-size: 12px; text-decoration: none;">← Volver al inicio</a>
        </div>

        <div class="input-group">
            <input type="text" name="curp" required maxlength="18" minlength="18"
                   pattern="[A-Z0-9]{18}" oninput="this.value = this.value.toUpperCase().trim()" placeholder=" ">
            <label>CURP (18 caracteres)</label>
            <span class="barra-brillo"></span>
        </div>

        <div class="input-group">
            <input type="text" name="licencia" required minlength="8" maxlength="15"
                   oninput="this.value = this.value.toUpperCase().trim()" placeholder=" ">
            <label>Número de Licencia</label>
            <span class="barra-brillo"></span>
        </div>

        <div class="input-group">
            <input type="text" name="numero_ine" required minlength="10" maxlength="13" placeholder=" ">
            <label>Número de INE</label>
            <span class="barra-brillo"></span>
        </div>

        <div class="input-group">
            <input type="text" name="placas" required minlength="6" maxlength="8"
                   oninput="this.value = this.value.toUpperCase().trim()" placeholder=" ">
            <label>Placas del vehículo</label>
            <span class="barra-brillo"></span>
        </div>

        <div class="input-group">
            <input type="text" name="modelo" required placeholder=" ">
            <label>Modelo (Ej: Versa 2022)</label>
            <span class="barra-brillo"></span>
        </div>

        <div class="input-group">
            <input type="text" name="color" required placeholder=" ">
            <label>Color del vehículo</label>
            <span class="barra-brillo"></span>
        </div>

        <div class="input-group">
            <input type="number" name="asientos" required min="1" max="15" placeholder=" ">
            <label>Número de asientos</label>
            <span class="barra-brillo"></span>
        </div>

        <div class="input-group-file">
            <label>Foto de tu INE:</label><br><br>
            <input type="file" name="foto_ine" accept="image/*" required>
        </div>

        <button type="submit" class="btn-conductor">
            FINALIZAR REGISTRO
        </button>
    </form>
</div>

<script>
document.getElementById("register-form").addEventListener("submit", async (e) => {
    e.preventDefault();

    Swal.fire({
        title: 'Procesando...',
        text: 'Guardando tus datos en UTranzit',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const formData = new FormData(e.target);

    try {
        const response = await fetch("procesar_conductor.php", { 
            method: "POST", 
            body: formData 
        });
        
        const text = await response.text();

        if (text.trim() === "ok") {
            Swal.fire({
                title: '¡Registro Exitoso!',
                text: 'Bienvenido al equipo. Redirigiendo...',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'panel_conductor.php';
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: text
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Problema de conexión con el servidor.'
        });
    }
});
</script>

</body>
</html>