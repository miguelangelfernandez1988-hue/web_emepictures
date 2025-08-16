<?php include 'includes/header.php'; 

//session_start();

define('TIEMPO_MAXIMO_SESION', 60); // 30 minutos

if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_inactivo = time() - $_SESSION['ultimo_acceso'];

    if ($tiempo_inactivo > TIEMPO_MAXIMO_SESION) {
        session_unset();     // Elimina variables de sesión
        session_destroy();   // Destruye la sesión
        header("Location: index.php?mensaje=sesion_expirada");
        exit();
    }
}

// Actualiza el tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();
?>
    <h3>Bienvenido, <?= $_SESSION['usuario'] ?></h3>
    <p>Usa el menú para gestionar socios o pagos.</p>
</div> <!-- Cierra el <div class="container py-4"> abierto en header.php -->
</body>
</html>
