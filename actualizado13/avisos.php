<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>UTranzit - Avisos Dinámicos</title>
    <link rel="stylesheet" href="style6.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<header class="utsc-header">
    <div class="header-contenedor">
        <span class="logo-utsc">UTSC</span>
        
        <button onclick="volverAlInicio()" class="btn-volver">
            <i class="fa-solid fa-arrow-left"></i> Volver a Ruta a la UT
        </button>
    </div>
</header>

<script>
function volverAlInicio() {
    window.location.href = 'pantalla1.html';
}
</script>

<body>

<div class="admin-toolbar">
    <div class="form-container">
        <h3><i class="fa-solid fa-plus-circle"></i> Nuevo Aviso</h3>
        <form action="guardar_aviso.php" method="POST" class="inline-form">
            <input type="text" name="titulo" placeholder="Título" required>
            <textarea name="contenido" placeholder="Mensaje..." required></textarea>
            <select name="tipo">
                <option value="normal">Normal</option>
                <option value="urgente">Urgente</option>
                <option value="mantenimiento">Mantenimiento</option>
            </select>
            <button type="submit" class="btn-publicar">Publicar</button>
        </form>
    </div>

    <div class="search-container">
        <h3><i class="fa-solid fa-magnifying-glass"></i> Buscar Aviso</h3>
        <form action="avisos.php" method="GET" class="inline-form">
            <input type="text" name="buscar" placeholder="Palabra clave..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
            <button type="submit" class="btn-buscar">Filtrar</button>
        </form>
    </div>
</div>

<div class="avisos-container">
    <h2 class="section-title">
        <?php echo (isset($_GET['buscar']) && $_GET['buscar'] != '') ? "Resultados de búsqueda" : "Avisos Recientes"; ?>
    </h2>

    <?php
    $conexion = mysqli_connect("localhost", "root", "", "utranzit");

    if (!$conexion) {
        echo "<p>Error de conexión</p>";
    } else {
        $busqueda = isset($_GET['buscar']) ? mysqli_real_escape_string($conexion, $_GET['buscar']) : '';
        $query = "SELECT * FROM avisos";
        
        if ($busqueda != '') {
            $query .= " WHERE titulo LIKE '%$busqueda%' OR contenido LIKE '%$busqueda%'";
        }
        
        $query .= " ORDER BY id DESC";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $claseTipo = $fila['tipo']; 
                $claseBorde = ($claseTipo == 'urgente') ? 'urgente' : '';
                ?>

                <div class="aviso-card <?php echo $claseBorde; ?> <?php echo $claseTipo; ?>">
                    <div class="aviso-header">
                        <span class="tag <?php echo $claseTipo; ?>">
                            <?php echo strtoupper($claseTipo); ?>
                        </span>
                        
                        <a href="eliminar_aviso.php?id=<?php echo $fila['id']; ?>" 
                           onclick="return confirm('¿Estás seguro de eliminar este aviso?')" 
                           style="color: #ff4d4d; float: right; text-decoration: none; font-size: 1.2rem; margin-left: 15px;">
                           <i class="fa-solid fa-trash-can"></i>
                        </a>

                        <span class="fecha"><?php echo $fila['fecha']; ?></span>
                    </div>
                    <div class="aviso-body">
                        <h3><?php echo $fila['titulo']; ?></h3>
                        <p><?php echo $fila['contenido']; ?></p>
                    </div>
                </div>

                <?php
            }
        } else {
            echo "<p style='text-align:center; color: white;'>No hay avisos para mostrar.</p>";
        }
    }
    ?>
</div>

</body>
</html>