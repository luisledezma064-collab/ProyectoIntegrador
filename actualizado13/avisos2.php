<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>UTranzit - Avisos</title>
    <link rel="stylesheet" href="style6.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<header class="utsc-header">
    <div class="header-contenedor">
        <span class="logo-utsc">UTSC</span>
        
        <button onclick="volverAlPanel()" class="btn-volver">
            <i class="fa-solid fa-house-user"></i> Volver al Panel
        </button>
    </div>
</header>

<script>
function volverAlPanel() {
    window.location.href = 'panel_conductor.php';
}
</script>

<body>

<div class="admin-toolbar" style="display: flex; justify-content: center;">
    <div class="search-container">
        <h3><i class="fa-solid fa-magnifying-glass"></i> Buscar Aviso</h3>
        
        <form action="" method="GET" class="inline-form">
            <input type="text" name="buscar" placeholder="Palabra clave..." 
                   value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
            <button type="submit" class="btn-buscar">Filtrar</button>
            
            <?php if(isset($_GET['buscar']) && $_GET['buscar'] != ''): ?>
                <a href="?" style="margin-left: 10px; color: #fff; font-size: 0.8rem;">Limpiar</a>
            <?php endif; ?>
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
        // Lógica de búsqueda
        $busqueda = isset($_GET['buscar']) ? mysqli_real_escape_string($conexion, $_GET['buscar']) : '';
        $query = "SELECT * FROM avisos";
        
        if ($busqueda != '') {
            $query .= " WHERE titulo LIKE '%$busqueda%' OR contenido LIKE '%$busqueda%'";
        }
        
        $query .= " ORDER BY id DESC";
        
        $resultado = mysqli_query($conexion, $query);

        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $claseTipo = $fila['tipo']; 
                $claseBorde = ($claseTipo == 'urgente') ? 'urgente' : '';
                ?>

                <div class="aviso-card <?php echo $claseBorde; ?> <?php echo $claseTipo; ?>">
                    <div class="aviso-header">
                        <span class="tag <?php echo $claseTipo; ?>">
                            <?php echo strtoupper($claseTipo); ?>
                        </span>
                        
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
            echo "<p style='text-align:center; color: white;'>No se encontraron avisos con esa palabra.</p>";
        }
    }
    ?>
</div>

</body>
</html>