<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'includes/db.php';
$error = $msg = '';
$token = $_GET['token'] ?? '';

if (!$token) {
    die('Token inválido.');
}

// Busca usuario con token válido y no expirado
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE reset_token = ? AND reset_expira > NOW()");
$stmt->execute([$token]);
$usuario = $stmt->fetch();

if (!$usuario) {
    die('Token inválido o expirado.');
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass1 = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if (!$pass1 || !$pass2) {
        $error = "Por favor, completa ambos campos.";
    } elseif ($pass1 !== $pass2) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($pass1) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Hashear la contraseña (usa password_hash)
        $passHash = password_hash($pass1, PASSWORD_DEFAULT);

        // Actualizar contraseña y eliminar token
        $stmt = $pdo->prepare("UPDATE usuarios SET clave = ?, reset_token = NULL, reset_expira = NULL WHERE id = ?");
        $stmt->execute([$passHash, $usuario['id']]);

        $msg = "Contraseña actualizada correctamente. Ya puedes iniciar sesión.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Restablecer contraseña</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height:100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4 shadow">
                <h4 class="text-center">Restablecer contraseña</h4>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <?php if ($msg): ?>
                    <div class="alert alert-success"><?= $msg ?></div>
                    <a href="index.php" class="btn btn-primary w-100">Iniciar sesión</a>
                <?php else: ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva contraseña</label>
                            <input type="password" class="form-control" name="password" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="password2" class="form-label">Repetir contraseña</label>
                            <input type="password" class="form-control" name="password2" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar nueva contraseña</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
