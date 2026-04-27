<div class="admin-panel">
    <h3>Crear Nuevo Aviso</h3>
    <form action="guardar_aviso.php" method="POST">
        <input type="text" name="titulo" placeholder="Título del aviso (ej. Retraso Ruta 4)" required>
        
        <textarea name="contenido" placeholder="Escribe el mensaje aquí..." required></textarea>
        
        <select name="tipo">
            <option value="urgente">Urgente (Rojo)</option>
            <option value="normal">Normal (Verde)</option>
            <option value="mantenimiento">Mantenimiento (Azul)</option>
        </select>
        
        <button type="submit">Publicar Aviso</button>
    </form>
</div>S