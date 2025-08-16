<?php
session_start();
require 'includes/db.php';
require 'includes/auth.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registro'])) {
    $nuevoUsuario = trim($_POST['nuevo_usuario']);
    $nuevaClave = $_POST['nueva_clave'];
    $confirmarClave = $_POST['confirmar_clave'];

    if (!filter_var($nuevoUsuario, FILTER_VALIDATE_EMAIL)) {
        $error = 'Debes introducir un correo electrónico válido.';
    } elseif (strlen($nuevaClave) < 4) {
        $error = 'La clave debe tener al menos 4 caracteres.';
    } elseif ($nuevaClave !== $confirmarClave) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (registrar($nuevoUsuario, $nuevaClave)) {
        $exito = 'Usuario creado correctamente. Ya puedes <a href="index.php">iniciar sesión</a>.';
    } else {
        $error = 'Error al registrar. Puede que el usuario ya exista.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - CRM TBP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height:100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card p-4 shadow">
                <h4 class="text-center">Crear cuenta</h4>
                <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                <?php if ($exito): ?><div class="alert alert-success"><?= $exito ?></div><?php endif; ?>

                <form method="post">
                    <input type="hidden" name="registro" value="1">
                    <div class="mb-3">
                        <label for="nuevo_usuario" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="nuevo_usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="nueva_clave" class="form-label">Nueva clave</label>
                        <input type="password" class="form-control" name="nueva_clave" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmar_clave" class="form-label">Confirmar clave</label>
                        <input type="password" class="form-control" name="confirmar_clave" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Registrarse</button>
                    <div class="text-center mt-2">
                        <a href="index.php">Volver al inicio de sesión</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
</body>
</html>
