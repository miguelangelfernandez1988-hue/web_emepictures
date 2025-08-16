<?php
require 'includes/header.php';
require 'includes/db.php';
require 'includes/functions.php';

$pagos = obtenerPagos($pdo);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagos - CRM Gimnasio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h4>Historial de Pagos</h4>
    <table class="table table-bordered">
        <thead><tr><th>Socio</th><th>Fecha</th><th>Monto</th><th>Método</th><th>Obs.</th></tr></thead>
        <tbody>
            <?php foreach ($pagos as $pago): ?>
            <tr>
                <td>
				<?= $pago['nombre'] ?>
				<?php if ($pago['estado'] !== 'activo'): ?>
					<br><a href="crear_pago.php?id=<?= $pago['socio_id'] ?>" class="btn btn-success btn-sm mt-1">💳 Pagar ahora</a>
				<?php endif; ?>
				</td>
                <td><?= $pago['fecha_pago'] ?></td>
                <td>€<?= $pago['monto'] ?></td>
                <td><?= $pago['metodo_pago'] ?></td>
                <td><?= $pago['observaciones'] ?></td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
