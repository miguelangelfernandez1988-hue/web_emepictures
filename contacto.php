<?php
// contacto.php
?>
<form id="castingForm" method="post" enctype="multipart/form-data">
    <!-- Tus campos actuales -->
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Mensaje:</label>
    <textarea name="mensaje" required></textarea>

    <!-- Botón -->
    <button type="submit">Enviar</button>

    <!-- Contenedor para mensajes -->
    <div id="form-message" style="margin-top:10px;color:#fff;"></div>
</form>
