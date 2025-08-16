<?php
require 'includes/db.php';

$session_id = $_GET['session_id'] ?? null;

// Aquí deberías hacer una llamada a la API de Stripe o, si no tienes `session_id` válido porque usas enlaces,
// puedes pedir que Stripe te notifique vía webhook y ahí activar al usuario por email.

$email = $_GET['email'] ?? null;

if ($email) {
    $stmt = $pdo->prepare("UPDATE usuarios SET activo = 1 WHERE email = ?");
    $stmt->execute([$email]);
    echo "Suscripción activada correctamente.";
} else {
    echo "Gracias por tu suscripción. Verificaremos tu pago.";
}