<?php
require 'includes/stripe_config.php';
require 'includes/db.php';

$socio_id = $_GET['id'] ?? null;
if (!$socio_id) die('ID de socio no especificado');

// Consultamos al socio
$stmt = $pdo->prepare("SELECT * FROM socios WHERE id = ?");
$stmt->execute([$socio_id]);
$socio = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$socio) die('Socio no encontrado');



// Crear sesión de pago
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'eur',
            'product_data' => ['name' => 'Mensualidad - ' . $socio['nombre']],
            'unit_amount' => 3000, // en céntimos (30€)
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://localhost/tbp/crm/success.php',
    'cancel_url' => 'http://localhost/tbp/crm/cancel.php',
    'metadata' => [
        'socio_id' => $socio_id
    ]
]);

header("Location: " . $session->url);
exit;
