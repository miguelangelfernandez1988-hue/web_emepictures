<?php
function login($usuario, $clave) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user && password_verify($clave, $user['clave']);
}



function registrar($usuario, $clave) {
    global $pdo;

    // Verifica si ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    if ($stmt->fetchColumn() > 0) {
        return false; // ya existe
    }

    // Crear nuevo
    $hash = password_hash($clave, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, clave) VALUES (?, ?)");
    return $stmt->execute([$usuario, $hash]);
}
?>

