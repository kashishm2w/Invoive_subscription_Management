<?php 

namespace App\Helpers;
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
  require_once APP_ROOT . '/vendor/autoload.php';

   class Mailer{
    public static function send(string $toEmail, $toName, string $subject, string$body): bool{
 $mail =new PHPMailer(true);
 try{
$mail->isSMTP();
$mail->Host='smtp.gmail.com';
$mail->SMTPAuth=true;
$mail->Username='k.2821135@gmail.com';
$mail->Password='mnwr knll xlwe exkv';
$mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
// $mail->SMTPDebug = 2;
$mail->Port=587;
    $mail->setFrom('k.2821135@gmail.com','Invoice App');
$mail->addAddress($toEmail, $toName);
$mail->isHTML(true);
$mail->Subject=$subject;
    $mail->Body=$body;
     return $mail->send();
 }
 catch (Exception $e) {
            error_log('Mail error: ' . $mail->ErrorInfo);
            return false;
 }

    }
   }