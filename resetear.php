<?php
require 'includes/db.php';
$error = $msg = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE reset_token = ? AND reset_expira > NOW()");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        $error = "Token inválido o expirado.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE usuarios SET clave = ?, reset_token = NULL, reset_expira = NULL WHERE reset_token = ?");
    if ($stmt->execute([$clave, $token])) {
        $msg = "Contraseña actualizada correctamente. <a href='login.php'>Iniciar sesión</a>";
    } else {
        $error = "Error al actualizar la contraseña.";
    }
} else {
    $error = "Token no válido.";
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
                <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
                <?php if (isset($usuario)): ?>
                <form method="post">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    <div class="mb-3">
                        <label for="clave" class="form-label">Nueva clave</label>
                        <input type="password" class="form-control" name="clave" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Actualizar</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
