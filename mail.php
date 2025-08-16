<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // O cambia a tu ruta si no usas Composer

$error = $msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'includes/db.php';
	require 'includes/auth.php';

    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $pdo->prepare("UPDATE usuarios SET reset_token = ?, reset_expira = ? WHERE usuario = ?");
        $stmt->execute([$token, $expira, $email]);

        $enlace = "http://www.emepictures.com/resetear2.php?token=$token"; // Cambia a tu dominio
        $asunto = "Recuperación de contraseña";
        $mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña:\n$enlace";

        // ENVÍO CON PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambia si usas otro servidor
            $mail->SMTPAuth = true;
            $mail->Username = 'eme.nopor@gmail.com'; // Tu correo real
            $mail->Password = 'onbd pwty wqge fskv'; // App password si usas Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('miguel_angel_fernandez1988@hotmail.com', 'Tu Sitio Web');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = "Hola,<br><br>Haz clic en el siguiente enlace para restablecer tu contraseña:<br><br>
                            <a href='$enlace'>$enlace</a><br><br>Este enlace expirará en 1 hora.";

            $mail->send();
            $msg = "Se ha enviado un enlace a tu correo para restablecer la contraseña.";
        } catch (Exception $e) {
            $error = "Error al enviar el correo: {$mail->ErrorInfo}";
        }

    } else {
        $error = "No se encontró ese correo.";
    }
}
?>