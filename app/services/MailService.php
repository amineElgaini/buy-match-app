<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public static function send(string $to, string $subject, string $body, ?string $attachmentPath = null): bool
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'amineelgaini1444@gmail.com';
            $mail->Password   = 'qytlxegxweeaoned'; // Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('amineelgaini1444@gmail.com', 'My App');
            $mail->addAddress($to);

            // Attach file if provided
            if ($attachmentPath) {
                if (!file_exists($attachmentPath)) {
                    throw new Exception("Attachment not found: " . $attachmentPath);
                }
                $mail->addAttachment($attachmentPath);
            }

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            return $mail->send();
        } catch (Exception $e) {
            error_log("Mail error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
