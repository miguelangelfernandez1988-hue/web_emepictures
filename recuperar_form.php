<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$error = $msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'includes/db.php';

    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $pdo->prepare("UPDATE usuarios SET reset_token = ?, reset_expira = ? WHERE usuario = ?");
        $stmt->execute([$token, $expira, $email]);

        $enlace = "http://www.emepictures.com/resetear2.php?token=$token"; // Cambia por tu dominio real
        $asunto = "Recuperación de contraseña";

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'eme.nopor@gmail.com'; // Debe coincidir con setFrom
            $mail->Password = 'onbd pwty wqge fskv';  // App password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('eme.nopor@gmail.com', 'CRM TBP'); // CORREGIDO: igual que Username
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = "Hola,<br><br>Haz clic en el siguiente enlace para restablecer tu contraseña:<br><br>
                          <a href='$enlace'>$enlace</a><br><br>Este enlace expirará en 1 hora.";

            $mail->send();
            $msg = "Se ha enviado un enlace a tu correo para restablecer la contraseña.";
        } catch (Exception $e) {
            $error = "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    } else {
        $error = "No se encontró ese correo.";
    }
}
?>

<div class="ui form">
    <?php if ($error): ?>
        <div class="ui negative message"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($msg): ?>
        <div class="ui positive message"><?= $msg ?></div>
    <?php endif; ?>

    <?php if (!$msg): ?>
       <form method="POST" action="recuperar_nuevo.php">
    <label for="email">Introduce tu correo:</label>
    <input type="email" name="email" required>
    <button type="submit" name="enviar">Enviar</button>
</form>

    <?php endif; ?>
</div>
