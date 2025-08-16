<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago cancelado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5 text-center">
    <h2 class="text-danger">❌ Pago cancelado</h2>
    <p>No se ha completado el proceso de pago. Puedes volver a intentarlo más tarde.</p>
    <a href="pagos.php" class="btn btn-secondary mt-3">Volver</a>
</div>
</body>
</html>
