<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login Conductor</title>
<link rel="stylesheet" href="STYLE5.css">
</head>

<body class="fondo">

<div class="c1">

<h1 class="h2">UTranzit</h1>
<h2 class="h3">INICIO DE SESIÓN CONDUCTOR</h2>

<section class="login-section">

<form action="login_conductor_procesar.php" method="POST">

<a href="registro_conductor.php">← Volver</a>

<div class="input-group">
<input type="text" name="numero_ine" required placeholder=" ">
<label>Número de INE</label>
<span class="barra-brillo"></span>
</div>

<div class="input-group">
<input type="text" name="licencia" required placeholder=" ">
<label>Número de Licencia</label>
<span class="barra-brillo"></span>
</div>

<button type="submit" class="btn-moderno">
INICIAR SESIÓN
</button>

</form>

</section>
</div>

</body>
</html>