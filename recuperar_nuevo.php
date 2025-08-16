<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'includes/db.php'; // Aquí usas $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Verificar que el email existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 1) {
        // Generar token
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar el token y su expiración
        $stmt_update = $pdo->prepare("UPDATE usuarios SET reset_token = ?, reset_expira = ? WHERE usuario = ?");
        $stmt_update->execute([$token, $expira, $email]);

        // Enviar correo
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'eme.nopor@gmail.com'; // Debe coincidir con setFrom
            $mail->Password = 'onbd pwty wqge fskv';  // App password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('TU_CORREO@hotmail.com', 'Soporte');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body    = "Haz clic en el siguiente enlace para restablecer tu contraseña:<br>
                              <a href='http://www.emepictures.com/resetear2.php?token=$token'>Recuperar contraseña</a>";
            $mail->AltBody = "Copia este enlace en tu navegador: http://www.emepictures.com/reset_password.php?token=$token";

            $mail->send();
            echo 'Correo enviado correctamente. Revisa tu bandeja de entrada.';
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "El correo no está registrado.";
    }
}
?>
