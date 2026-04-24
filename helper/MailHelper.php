<?php
require_once __DIR__ . './../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * Class MailHelper
 * 
 * Handles email sending using PHPMailer and SMTP configuration.
 */

class MailHelper
{

    /**
     * Send an email
     *
     * @param string $email Recipient email address
     * @param string $subject Email subject
     * @param string $message Email body (HTML supported)
     * 
     * @return array Status and message OR error details
     */
    public static function sendEmail($email, $subject, $message)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['emailhost'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['emailusername'];
            $mail->Password   = $_ENV['emailpassword'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['emailport'];

            $mail->CharSet = 'UTF-8';

            $mail->setFrom($_ENV['emailusername'], $_ENV['name']);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();

            return ["status" => true, "message" => "Success"];
        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $mail->ErrorInfo . " | " . $e->getMessage()
            ];
        }
    }
}
