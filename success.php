<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php'; // Stripe SDK
require 'includes/db.php';
require 'includes/auth.php';

// Validar parámetros
if (!isset($_GET['email']) || !isset($_GET['session_id'])) {
    echo "Parámetros faltantes.";
    exit;
}

$email = $_GET['email'];
$session_id = $_GET['session_id'];

// Clave secreta Stripe
\Stripe\Stripe::setApiKey('');

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    if ($session && $session->payment_status === 'paid') {
        // Opcional: puedes obtener plan desde metadata o price_id si usas suscripciones
        // Para simplificar, dejamos el plan que ya se guardó al crear usuario

        // Activar usuario
        $stmt = $pdo->prepare("UPDATE usuarios SET activo = 1 WHERE usuario = ?");
        $stmt->execute([$email]);

        echo "¡Suscripción activada correctamente!";
    } else {
        echo "El pago no fue exitoso.";
    }
} catch (Exception $e) {
    echo "Error al procesar la suscripción: " . $e->getMessage();
}
?>
