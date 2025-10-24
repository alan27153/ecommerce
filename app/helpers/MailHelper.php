<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Si usas Composer:
require_once __DIR__ . '/../../vendor/autoload.php';

class MailHelper {

    /**
     * Envía un correo electrónico de verificación con un código al usuario.
     *
     * @param string $toEmail Correo del destinatario.
     * @param string $verificationCode Código de verificación.
     * @return bool True si el correo se envió correctamente, false si ocurrió un error.
     */
    public static function sendVerificationEmail(string $toEmail, string $verificationCode): bool {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP (Gmail)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;

            // ⚠️ Usa una contraseña de aplicación de Gmail (no tu contraseña normal)
            $mail->Username   = 'adsantoss@ucvvirtual.edu.pe'; // tu correo Gmail
            $mail->Password   = 'douppwjcqpwnkcdl'; // clave generada desde app passwords
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Datos del remitente y destinatario
            $mail->setFrom('tucorreo@gmail.com', 'Soporte Ecommerce');
            $mail->addAddress($toEmail);

            // Contenido del mensaje
            $mail->isHTML(true);
            $mail->Subject = 'Verifica tu cuenta';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 10px;'>
                    <h2>Verificación de cuenta</h2>
                    <p>Gracias por registrarte. Tu código de verificación es:</p>
                    <h1 style='color:#2b6cb0;'>$verificationCode</h1>
                    <p>Ingresa este código en la página de verificación para activar tu cuenta.</p>
                    <br>
                    <p>Si no solicitaste esta cuenta, puedes ignorar este mensaje.</p>
                </div>
            ";

            $mail->AltBody = "Tu código de verificación es: $verificationCode";

            // Enviar correo
            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Error al enviar correo de verificación: {$mail->ErrorInfo}");
            return false;
        }
    }
}
