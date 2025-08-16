<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'includes/db.php';
require 'includes/auth.php';
require 'vendor/autoload.php'; // Stripe SDK

\Stripe\Stripe::setApiKey('');

// Recoger datos POST
$email = trim($_POST['email']);
$password = $_POST['password'];
$plan = $_POST['plan']; // 'mensual', 'trimestral' o 'anual'

// Validación básica
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 4) {
    http_response_code(400);
    echo 'Datos inválidos';
    exit;
}

// Verificar si ya existe el email
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo 'El email ya está registrado';
    exit;
}

// Hash de contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);

// Mapear plan a price_id de Stripe (reemplaza con tus price_ids reales)
$priceIds = [
    'mensual' => '',
    'trimestral' => '',
    'anual' => ''
];

if (!isset($priceIds[$plan])) {
    http_response_code(400);
    echo 'Plan inválido';
    exit;
}

// Guardar usuario con activo=0
$stmt = $pdo->prepare("INSERT INTO usuarios (usuario, clave, plan, activo) VALUES (?, ?, ?, 0)");
$ok = $stmt->execute([$email, $hash, $plan]);

if (!$ok) {
    http_response_code(500);
    echo 'Error al registrar usuario';
    exit;
}

// Crear sesión de pago Stripe Checkout
try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price' => $priceIds[$plan],
            'quantity' => 1,
        ]],
        'mode' => 'subscription',
        'customer_email' => $email, // Opcional para facilitar autofill en Stripe
        'success_url' => "http://www.emepictures.com/success.php?session_id={CHECKOUT_SESSION_ID}&email=" . urlencode($email),
        'cancel_url' => "http://www.emepictures.com/cancel.php?email=" . urlencode($email),
        // Opcional: metadata para más info
        'metadata' => [
            'usuario_email' => $email,
            'plan' => $plan
        ],
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo "Error creando sesión de pago: " . $e->getMessage();
    exit;
}

// Devuelve al frontend el ID de la sesión para redirigir
echo json_encode([
    'status' => 'ok',
    'sessionId' => $session->id
]);
