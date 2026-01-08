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


    public static function sendInvoiceEmail(int $userId, int $invoiceId): bool
    {
        try {
            // Get user details
            $userModel = new \App\Models\User();
            $user = $userModel->getById($userId);
            
            if (!$user || empty($user['email'])) {
                error_log("Cannot send invoice email: User not found or no email for user ID $userId");
                return false;
            }

            // Get invoice details
            $invoiceModel = new \App\Models\Invoice();
            $invoice = $invoiceModel->getById($invoiceId);
            
            if (!$invoice) {
                error_log("Cannot send invoice email: Invoice not found for ID $invoiceId");
                return false;
            }

            // Get invoice items
            $itemModel = new \App\Models\InvoiceItem();
            $items = $itemModel->getByInvoice($invoiceId);

            // Get company details (global company settings)
            $companyModel = new \App\Models\Company();
            $company = $companyModel->getFirst();
            
            // Fallback company details if not set
            if (!$company) {
                $company = [
                    'company_name' => 'Invoice and Sub',
                    'email' => '',
                    'phone' => '',
                    'address' => '',
                    'tax_number' => ''
                ];
            }

            // Render email template
            ob_start();
            require APP_ROOT . '/app/Views/invoice/email_template.php';
            $emailBody = ob_get_clean();

            // Send email
            $subject = 'Invoice ' . $invoice['invoice_number'] . ' - Payment Confirmation';
            return self::send($user['email'], $user['name'], $subject, $emailBody);

        } catch (\Exception $e) {
            error_log('Invoice email error: ' . $e->getMessage());
            return false;
        }
    }
   }