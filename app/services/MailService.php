<?php

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    public static function send($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'amineelgaini1444@gmail.com';
        $mail->Password   = 'qytlxegxweeaoned';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('amineelgaini1444@gmail.com', 'My App');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        return $mail->send();
    }
}
