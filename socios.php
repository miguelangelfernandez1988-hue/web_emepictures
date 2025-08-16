<?php
require 'includes/header.php';
require 'includes/db.php';
require 'includes/functions.php';

$socios = obtenerSocios($pdo);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h4>Listado de Socios</h4>
    <table class="table table-striped">
        <thead><tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Estado</th></tr></thead>
        <tbody>
            <?php foreach ($socios as $socio): ?>
            <tr>
                <td><?= $socio['nombre'] ?></td>
                <td><?= $socio['email'] ?></td>
                <td><?= $socio['telefono'] ?></td>
                <td><?= $socio['estado'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
