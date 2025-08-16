<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$destinatario = "eme.nopor@gmail.com";
$error = $msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = htmlspecialchars(trim($_POST['nombre']));
    $email   = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $mensaje = htmlspecialchars(trim($_POST['mensaje']));

    if (!$email) {
        die("Correo electrónico no válido.");
    }

    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'eme.nopor@gmail.com';
        $mail->Password = 'onbd pwty wqge fskv'; // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('eme.nopor@gmail.com', 'Formulario Web');
        $mail->addAddress($destinatario);
        $mail->addReplyTo($email, $nombre);

        // Tipos y tamaño permitidos
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2 MB

        // Adjuntar imagen1
        if (!empty($_FILES['imagen1']['tmp_name'])) {
            $mime1 = mime_content_type($_FILES['imagen1']['tmp_name']);
            if (in_array($mime1, $allowedTypes) && $_FILES['imagen1']['size'] <= $maxSize) {
                $mail->addAttachment($_FILES['imagen1']['tmp_name'], $_FILES['imagen1']['name']);
            } else {
                $error .= "Imagen 1: formato no permitido o tamaño excedido.<br>";
            }
        }

        // Adjuntar imagen2 (opcional)
        if (!empty($_FILES['imagen2']['tmp_name'])) {
            $mime2 = mime_content_type($_FILES['imagen2']['tmp_name']);
            if (in_array($mime2, $allowedTypes) && $_FILES['imagen2']['size'] <= $maxSize) {
                $mail->addAttachment($_FILES['imagen2']['tmp_name'], $_FILES['imagen2']['name']);
            } else {
                $error .= "Imagen 2: formato no permitido o tamaño excedido.<br>";
            }
        }

        if (!$error) {
            $mail->isHTML(true);
            $mail->Subject = "Nuevo mensaje de contacto";
            $mail->Body = "
                <b>Nombre:</b> {$nombre}<br>
                <b>Email:</b> {$email}<br><br>
                <b>Mensaje:</b><br>" . nl2br($mensaje) . "
            ";

            $mail->send();
            echo "<script>alert('Mensaje enviado correctamente.'); window.location='contacto.php';</script>";
        } else {
            echo "<script>alert('{$error}'); window.history.back();</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error al enviar el mensaje: {$mail->ErrorInfo}'); window.history.back();</script>";
    }
}
?>
